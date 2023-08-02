<?php


namespace App\Controller\Manager;

use App\Entity\Cra;
use App\Entity\Client;
use App\Entity\DayDetails;
use App\Entity\Calendrier;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Entity\Utilisateurs; // Make sure this use statement is correct
use App\Form\ManagerAssignmentType;

use App\Entity\CodeProjet;
use App\Form\CraEditType;
use App\Form\CraType;
use App\Repository\CodeProjetRepository;
use App\Repository\DayDetailsRepo;
use App\Repository\CraRepository;
use App\Repository\ClientRepository;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use TCPDF;
use DateTime;

/**
 * @Route ("/manager", name="manager_")
 */
class CraController extends AbstractController
{
    private $repository;
    private $userRepo;
    private $repository1;
    private $repository2;
    private $em;

    public function __construct(CraRepository $repository, ClientRepository $userRepo, CodeProjetRepository  $repository1, DayDetailsRepo $repository2, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->userRepo = $userRepo;
        $this->repository1 = $repository1;
        $this->repository2 = $repository2;
        $this->em = $em;
    }
/**
 * @Route("/manager/cra/new", name="cra_new")
 */
public function create(Request $request): Response
{

   // Récupérer la liste des CRA pour le mois en cours
   $currentMonth = (new \DateTime())->format('m');
   $month = $request->query->getInt('month', $currentMonth);
   $year = (new \DateTime())->format('Y');
   $startDate = new \DateTime(sprintf('%s-% s-01', $year, $month));
   $endDate = (clone $startDate)->modify('last day of this month');
   $craList = $this->getDoctrine()->getRepository(Calendrier::class)->findBy([
       'date' => [
           '>=', $startDate->format('Y-m-d'),
           '<=', $endDate->format('Y-m-d'),
       ],
   ]);
 
   // Créer un tableau pour afficher les dates et les valeurs
   $dates = [];
   $currentDate = (clone $startDate);
   while ($currentDate <= $endDate) {
    $dates[] = (clone $currentDate);
    $currentDate->modify('+1 day');
    }
   // Créer un nouvel objet Cra
   $cra = new Cra();
   $cra->setDate(new \DateTime()); 
    
   // Créer le formulaire à partir de la classe CraType et de l'objet Cra
   $form = $this->createForm(CraType::class, $cra);

   // Gérer la soumission du formulaire
   $form->handleRequest($request);

   // Vérifier si le formulaire a été soumis et est valide
   if ($form->isSubmitted() && $form->isValid()) {
    // Get the form data
    $formData = $form->getData();
    $routeParams = [
        'formData' => $formData, // Pass the form data as a parameter
    ];
    // Sauvegarder les données dans la base de données
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($cra);
    $entityManager->flush();

        // Store relevant data in session
        $request->getSession()->set('cra_data', $cra);

        // Redirect to the second step of registration
        $response = $this->forward('App\Controller\Manager\CraController::createDays', [
            'formData' => $formData,
        ]);
        
        return $response;
    }

   // Afficher le formulaire et les autres variables à l'utilisateur
   return $this->render('manager/cra/create.html.twig', [
       'form' => $form->createView(),
       'month' => strtolower(strftime('%B', $startDate->getTimestamp())),
       'days' => $dates,
   ]);
}
    /**
 * @Route("/manager/edit/days", name="edit_days")
 */
    public function createDays(Request $request)
    {
        $cra = $this->repository->findOneBy([], ['id' => 'DESC']);
        $dayValuesData = $request->request->get('values');
        $codeProjetList = $request->request->get('cra_codeProjet-list');
        $horraireList = $request->request->get('txtinput');
        $projectWorkList = $request->request->get('txtinput-work');

        $chosenMonth = $cra->getMonth();
        // Parse the chosen month
        $monthParts = explode(' ', $chosenMonth);
        $cmonthName = $monthParts[0];
        $cyear = $monthParts[1];
        
        $frToNumericMonth = [
            'janvier' => '01',
            'février' => '02',
            'mars' => '03',
            'avril' => '04',
            'mai' => '05',
            'juin' => '06',
            'juillet' => '07',
            'août' => '08',
            'septembre' => '09',
            'octobre' => '10',
            'novembre' => '11',
            'décembre' => '12',
        ];
        $numericMonth = $frToNumericMonth[strtolower($cmonthName)];

        // Create the start and end dates for the chosen month
        $cstartDate = DateTime::createFromFormat('Y-m-d', "$cyear-$numericMonth-01");
        $cendDate = (clone $cstartDate)->modify("last day of $cyear-$numericMonth");

        // Create an array to store the day dates
        $cdates = [];
        $cDate = (clone $cstartDate);
        while ($cDate <= $cendDate) {
            $cdates[] = (clone $cDate);
            $cDate->modify('+1 day');
        }
        $entityManager = $this->getDoctrine()->getManager();

        foreach ($cdates as $day) {
            $dayOfWeek = (int) $day->format('N');
            if ($dayOfWeek !== 6 && $dayOfWeek !== 7) {
            $date = $day->format('Y-m-d');
            $dayObject = DateTime::createFromFormat('Y-m-d', $date);
            $dayNumber = $day->format('j')-1;

            $value = $dayValuesData[floatval($day->format('d'))];
            $hours = $horraireList[$dayNumber];
            $workedproj = $projectWorkList[$dayNumber];
            $projname = $codeProjetList[$dayNumber];
            $projid = $this->repository1->findOneBy(['code_projet' => $projname]);
            // Create a new CraDetails entity
            $dayDetails = new DayDetails();
            $dayDetails->setCRAId($cra);
            $dayDetails->setDaysWorked($hours);
            $dayDetails->setDay($dayObject); 
            $dayDetails->setWorkDone($workedproj);
            $dayDetails->setHours($value);
            $dayDetails->setProjetID($projid);
        
            $entityManager->persist($dayDetails);}
        }
        
        $entityManager->flush();
        return $this->redirectToRoute('manager_download_receipt');   
    }
/**
     * @Route("/cra", name="cra")
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $cra = $this->repository->findBy(array(),array('id'=>'ASC'));
        
        return $this->render('Manager/client/index.html.twig', [
            "cra" => $cra,
        ]);
    }
       /**
     * @Route("/manager/download_receipt", name="download_receipt")
     */
    public function handleFormSubmission()
    {   
        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator('www.solway.com');
        $pdf->SetAuthor('SOLWAY');
        $pdf->SetTitle('CRA PDF');
        $pdf->SetSubject('Generated PDF');

        // Add a page
        $pdf->AddPage();

        $cra = $this->repository->findOneBy([], ['id' => 'DESC']);
        $craDetails = $this->repository2->findOneBy(['cra' => $cra->getId()]);
        $user = $this->getUser();

        // Extract the ID from the user identifier
        $userIdentifier = $user->getId();

        $client = $this->userRepo->findOneBy(['user_id' => $userIdentifier]);

        $codeProjetArray = [];
        $craArray = $this->repository2->findBy(['cra' => $cra->getId()]);        
        foreach ($craArray as $craDetail) {
            $codeProjet = $craDetail->getProjetID()->getCodeProjet();
            if (array_key_exists($codeProjet, $codeProjetArray)) {
                $codeProjetArray[$codeProjet]++;
            } else {
                $codeProjetArray[$codeProjet] = 1;
            }
        }
        $content = '';
        
        $content .= '
            <table class="table table-striped table-hover" style="font-size: 14px; border: 1px solid black; border-collapse: collapse;">
                <tbody>
                    <tr>
                        <th style="width: 25%; border: 1px solid black; border-collapse: collapse;">
                        <div style="display: flex; align-items: center;">
                        <img src="assets/Images/Logo_slogan.jpg" alt="logo" width="120px" height="45px">
                        </div>
                        </th>
                        <th style="width: 50%; border: 1px solid black; border-collapse: collapse;">
                        <div style=" display: flex; align-items: center; text-align: center;">
                        <br>FEUILLE D\'ATTACHEMENT<br>'. $cra->getMonth().'</div></th>
                        <th style="width: 25%; border: 1px solid black; border-collapse: collapse;">
                        <div style="display: flex; align-items: center; text-align: center;">
                        <br>Date: '.$cra->getDate()->format('d-m-Y').'</div></th>
                    </tr>
                    <tr>
         //               <th style="width: 100%; border: 1px solid black; border-collapse: collapse;text-align: center;">Nom Prénom: '.$client->getName().'</th>
                    </tr>
                    <tr>
                        <th style="width: 100%; border: 1px solid black; border-collapse: collapse;text-align: center;">Raison sociale client: ---</th>
                    </tr>
                    <tr>
                        <th style="width: 100%; border: 1px solid black; border-collapse: collapse;text-align: center;">Affaire: '.implode(', ', array_keys($codeProjetArray)).'</th>
                    </tr>
                    <tr>
                        <th style="width: 17%; border: 1px solid black; border-collapse: collapse;text-align: center;">Date</th>
                        <th style="width: 17%; border: 1px solid black; border-collapse: collapse;text-align: center;">Jour Travaillé</th>
                        <th style="width: 16%; border: 1px solid black; border-collapse: collapse;text-align: center;">Horaire</th>
                        <th style="width: 50%; border: 1px solid black; border-collapse: collapse;text-align: center;">Travaux Effectué</th>
                    </tr>
                ';

        $chosenMonth = $cra->getMonth();

        // Parse the chosen month
        $monthParts = explode(' ', $chosenMonth);
        $cmonthName = $monthParts[0];
        $cyear = $monthParts[1];

        $frToNumericMonth = [
            'janvier' => '01',
            'février' => '02',
            'mars' => '03',
            'avril' => '04',
            'mai' => '05',
            'juin' => '06',
            'juillet' => '07',
            'août' => '08',
            'septembre' => '09',
            'octobre' => '10',
            'novembre' => '11',
            'décembre' => '12',
        ];
        
        $numericMonth = $frToNumericMonth[strtolower($cmonthName)];
        
        // Create a DateTime object for the first day of the month
        $startDate = new \DateTime("$cyear-$numericMonth-01");

        // Get the last day of the month
        $endDate = clone $startDate;
        $endDate->modify('last day of');

        // Calculate the number of days in the month
        $daysInMonth = (int) $endDate->format('d');
        
        $currentDay = clone $startDate; 
        setlocale(LC_TIME, 'fr_FR.UTF-8');

        $dayNamesFrench = [
            1 => 'lundi',
            2 => 'mardi',
            3 => 'mercredi',
            4 => 'jeudi',
            5 => 'vendredi',
            6 => 'samedi',
            7 => 'dimanche',
        ];
        $total = 0.0;
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDay->setDate($startDate->format('Y'), $startDate->format('m'), $day);

            $dayOfWeek = $currentDay->format('N');
            $dayOfWeekFrench = $dayNamesFrench[$dayOfWeek];

            // Check if the day is a weekend (Saturday or Sunday)
            if ($dayOfWeek === '6' || $dayOfWeek === '7') {
                $content .= '<tr><td style="width: 17%; border: 1px solid black; border-collapse: collapse; text-align: center;">' .substr($dayOfWeekFrench, 0, 3). '. ' .$currentDay->format('d') . '</td>
                <td style="width: 17%;border: 1px solid black; border-collapse: collapse;"> </td>
                <td style="width: 16%;border: 1px solid black; border-collapse: collapse;"> </td>
                <td style="width: 50%;border: 1px solid black; border-collapse: collapse;"> </td></tr>';
            } else {
                $craDetails = $this->repository2->findOneBy(['cra' => $cra->getId(), 'day' => $currentDay]);        
            $content .= '
            <tr>
            <td style="width: 17%; border: 1px solid black; border-collapse: collapse;text-align: center;">' . substr($dayOfWeekFrench, 0, 3) . '. ' .$currentDay->format('d') . '</td>
            <td style="width: 17%; border: 1px solid black; border-collapse: collapse;text-align: center;">' . (($craDetails->getHours() === "1") ? "1,00 J" :  (($craDetails->getHours() === "0") ? "0,00 J" :"0,50 J")) . '</td>
            <td style="width: 16%; border: 1px solid black; border-collapse: collapse;">' . $craDetails->getDaysWorked() . '</td>
            <td style="width: 50%; border: 1px solid black; border-collapse: collapse;">' . $craDetails->getWorkDone() . '</td></tr>';
            $total += floatval($craDetails->getHours());    
        }
            $currentDay->modify('+1 day'); // Increment the current day
        }  
        // $total=0;
        // for ($projet = 1; $projet <= count($codeProjetArray); $projet++) {
        //     $codeproj = $this->repository1->findOneBy(['code_projet' => array_keys($codeProjetArray)[$projet - 1]]);        
        //     $sousTotal = ($codeProjetArray[array_keys($codeProjetArray)[$projet - 1]] * $codeproj->getTTC())+($codeProjetArray[array_keys($codeProjetArray)[$projet - 1]] * $codeproj->getTVA());
        //     $total += $sousTotal;
        //     $content .= '<tr><td style="border: 1px solid black; border-collapse: collapse;"> Sous Total pour ' . array_keys($codeProjetArray)[$projet - 1] . ':' . $sousTotal .'</td>
        //     <td style="border: 1px solid black; border-collapse: collapse;"> TTC:' . strval($codeProjetArray[array_keys($codeProjetArray)[$projet - 1]] * $codeproj->getTTC()) . '</td>
        //     <td style="border: 1px solid black; border-collapse: collapse;"> TVA:' . strval($codeProjetArray[array_keys($codeProjetArray)[$projet - 1]] * $codeproj->getTVA()) . '</td>
        //     </tr>';
        // }
        // $content .='<tr><td style="border: 1px solid black; border-collapse: collapse;"> Total:' . $total . '</td></tr>';
        $content .='<tr><td style="width:100%; border: 1px solid black; border-collapse: collapse;"> Total: ' . $total . 'j </td></tr>';
        $content .='<tr>
            <td style="width: 50%; border: 1px solid black; border-collapse: collapse; text-align: center;">
            visa client<br><br></td>
            <td style="width: 50%; border: 1px solid black; border-collapse: collapse;text-align: center;">
            visa interne<br><br></td></tr>
            </tbody>
        </table>';
        $pdf->writeHTML($content);
        return new Response($pdf->Output('facture.pdf', 'D'));
}

    /**
     * @Route("/download_table", name="download_table")
     */
    public function downloadTable(Request $request): Response
    {
        $id = $request->query->get('id');

        $pdf = new TCPDF();

        // Set document information
        $pdf->SetCreator('www.solway.com');
        $pdf->SetAuthor('SOLWAY');
        $pdf->SetTitle('CRA PDF');
        $pdf->SetSubject('Generated PDF');

        // Add a page
        $pdf->AddPage();

        $width = 50; // Adjust the width of the logo image
        $height = 15; // Adjust the height of the logo image

        $x = ($pdf->getPageWidth() - $width) / 2;

        $y = 15; // Adjust the vertical position as needed

        $pdf->Image('assets/Images/Logo_slogan.jpg', $x, $y, $width, $height);
        if (!$id) {
            // Handle the case when the 'id' parameter is not present in the URL
            throw new \InvalidArgumentException('The "id" parameter is missing.');
        }

        $cra = $this->repository->find($id);

        if (!$cra) {
            // Handle the case when the 'Cra' entity with the specified id does not exist
            throw $this->createNotFoundException('Cra not found.');
        }

        $content = '';
        $content .= '  
        <br /><br /><br /><br /><br /><h3 align="center"> CRA data </h3><br />
        <h2 align="center"> Les détails des cras de tout l\'utilisateur '.$cra->getId().' </h2> <br/><br/>';   

        
        // $date = $cra->getDate() instanceof \DateTimeInterface ? $cra->getDate()->format('d-m-Y') : '';
        $content .= ' <div style="font-size:14px; padding-left: 100px;"><br/><br/><br/> Projet: '.$cra->getCodeProjet()->getCodeProjet().'
        <br/>Jours travaillés: ' .$cra->getDaysInput(). '<br/>Mois: '.$cra->getMonth().'</div>';  

        $pdf->writeHTML($content); 
        
        // Output the PDF as a response
        return new Response($pdf->Output('table.pdf', 'D'));
    }


    /**
     * @Route("/cre_show/{id}", name="delete_cra")
     * @param CRA $cra
     * @return RedirectResponse
     */
    public function delete(Request $request): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $cra = $this->repository->find($id);
        $em->remove($cra);
        $em->flush();

        return $this->redirectToRoute('cra_show');

    }

    public function showEditDays(Request $request, $id): Response
    {
        $cra = $this->getDoctrine()->getRepository(CRA::class)->find($id);

        $dayDetails = $this->getDoctrine()->getRepository(DayDetails::class)->findBy(['cra' => $cra->getId()]);

        if (!$cra) {
            throw $this->createNotFoundException('CRA not found');
        }

        $codeProjets = $this->getDoctrine()->getRepository(CodeProjet::class)->findAll();

        dump($codeProjets);
        return $this->render('cra/edit.html.twig', [
            'cra' => $cra,
            'dayDetails' => $dayDetails,
            'codeProjets' => $codeProjets
        ]);
    }
    
    public function updateDays(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        $formData = json_decode($jsonData, true);
        $cra = $this->repository->findOneBy([], ['id' => 'DESC']);
        $dayDetails = $this->repository2->findBy(['cra' => $cra->getId()]);
        
        $entityManager = $this->getDoctrine()->getManager();
        $index = 0;
        $cra->setStatus($formData['status'][0]);
        foreach ($dayDetails as $dayDetail) {
            // Check if the corresponding index exists in the formData arrays
            $dayDetail->setHours($formData['hours'][$index]);
            $dayDetail->setDaysWorked($formData['daysWorked'][$index]);
            $dayDetail->setWorkDone($formData['workDone'][$index]);
            $projid = $this->repository1->findOneBy(['code_projet' => $formData['codeProjet'][$index]]);
            $dayDetail->setProjetID($projid);
            
            $index += 1;

            $entityManager->persist($dayDetail);
            }
    
        $entityManager->flush();

        return new JsonResponse(['status' => $formData]);
    }


    /**
      * @Route("/cra/modifier", name="cra_edit")
      */
      public function editCra(Request $request)
      {
        $id = $request->query->get('id');

        if (!$id) {
            // Handle the case when the 'id' parameter is not present in the URL
            throw new \InvalidArgumentException('The "id" parameter is missing.');
        }

        $cra = $this->repository->find($id);

        if (!$cra) {
            // Handle the case when the 'Cra' entity with the specified id does not exist
            throw $this->createNotFoundException('Cra not found.');
        }

        $form = $this->createForm(CraEditType::class, $cra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($cra);
            $em->flush();
            $this->addFlash('message', 'Cra mis à jour !');
            return $this->redirectToRoute('cra_show');
        }

        return $this->render('Manager/client/editCra.html.twig', [
            'form' => $form->createView(),
        ]);
      }
/**
     * @Route("/cra", name="cra_show")
     */
    public function show(Request $request): Response
   
        {
            // Récupérer la liste des CRA pour le mois en cours
            $currentMonth = (new \DateTime())->format('m');
            $month = $request->query->getInt('month', $currentMonth);
            $year = (new \DateTime())->format('Y');
            $startDate = new \DateTime(sprintf('%s-%s-01', $year, $month));
            $endDate = (clone $startDate)->modify('last day of this month');
            $craList = $this->getDoctrine()->getRepository(Cra::class)->findBy([
                'date' => [
                    '>=', $startDate->format('Y-m-d'),
                    '<=', $endDate->format('Y-m-d'),
                ],
            ]);
            setlocale(LC_TIME, 'fr_FR');

            // Créer un tableau pour afficher les dates et les valeurs
            $dates = [];
            $currentDate = (clone $startDate);
            while ($currentDate <= $endDate) {
                $dates[] = (clone $currentDate);
                $currentDate->modify('+1 day');
            }
        
            // Gérer la soumission du formulaire
            $form = $this->createFormBuilder();
            foreach ($dates as $date) {
                $form->add($date->format('Y-m-d'), CheckboxType::class, [
                    'label' => $date->format('d'),
                    'required' => false,
                    'data' => $craList[$date->format('Y-m-d')] ?? false,
                ]);
            }
            $form = $form->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $entityManager = $this->getDoctrine()->getManager();
                foreach ($data as $key => $value) {
                    $c = $this->getDoctrine()->getRepository(Cra::class)->findOneBy(['date' => $key]);
                    if ($cra) {
                        $cra->setValue($value ? 0.5 : 0);
                    } else {
                        $cra = new Cra();
                        $cra->setDate(new \DateTime($key));
                        $cra->setValue($value ? 0.5 : 0);
                    }
                    // Ajouter les informations de l'utilisateur
                    $user = $this->getUser();
                    $cra->setUser($user);
                    $cra->setMonth($month);
                    $cra->setYear($year);
                    $entityManager->persist($cra);
                }
                $entityManager->flush();
        
                // Enregistrer le nombre de jours entrés dans la base de données
                $count = count(array_filter($data));
                $user = $this->getUser();
                $user->setDaysEntered($count);
                $entityManager->persist($user);
                $entityManager->flush();
        
                $this->addFlash('success', 'Le Compte Rendu d\'Activité a été enregistré avec succès.');
                return $this->redirectToRoute('cra_index');
            }
        
            // Afficher le formulaire
            return $this->render('User/cra/index.html.twig', [
                'form' => $form->createView(),
                'month' => strtolower(strftime('%B', $startDate->getTimestamp())), // utiliser la fonction strftime() et strtolower() pour afficher le mois en lettres minuscules
                'days' => $dates,
            ]);
        }
         /**
     * @Route("/assign-users", name="assign_users")
     */
    public function assignUsers(Request $request)
    {
        $manager = $this->getUser(); // Get the currently logged-in manager user

        // Fetch all users to display in the form
        $userRepository = $this->getDoctrine()->getRepository(Utilisateurs::class);
        $allUsers = $userRepository->findAll();

        // Remove the manager from the list of users
        $users = array_filter($allUsers, function ($user) use ($manager) {
            return $user !== $manager;
        });

        $form = $this->createForm(ManagerAssignmentType::class, $manager, ['users' => $users]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // The form was submitted and is valid, so save the changes
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($manager);
            $entityManager->flush();

            return $this->redirectToRoute('manager_assign_users');
        }

        return $this->render('manager/assign_users.html.twig', [
            'form' => $form->createView(),
        ]);
    }
} 