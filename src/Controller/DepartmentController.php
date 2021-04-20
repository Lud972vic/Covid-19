<?php

namespace App\Controller;

use App\Service\CallApiService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DepartmentController extends AbstractController
{
    /**
     * @Route("/department/{department}", name="department")
     */
    public function index(string $department, CallApiService $callApiService, ChartBuilderInterface $chartBuilder): Response
    {
        $label = [];
        $hospitalises = [];
        $nouvellesHospitalisations = [];
        $reanimation = [];
        $nouvellesReanimations = [];
        $deces = [];
        $gueris = [];

        //On récupére une semaine de données
        for ($i = 1; $i < 8; $i++) {
            //la date du jour - 1 jour, -2 jours ... -7 jours
            $date = new DateTime('- ' . $i . ' day');
            $datas = $callApiService->getAllDataByDate($date->format('Y-m-d'));

            foreach ($datas['allFranceDataByDate'] as $data) {
                //On filtre juste sur le departement de l'URL passé en parametre
                if ($data['nom'] === $department) {

                    //On ajoute dans les tableaux, les nouvelles données
                    $label[] = $data['date'];
                    $hospitalises[] = $data['hospitalises'];
                    $nouvellesHospitalisations[] = $data['nouvellesHospitalisations'];
                    $reanimation[] = $data['reanimation'];
                    $nouvellesReanimations[] = $data['nouvellesReanimations'];
                    $deces[] = $data['deces'];
                    $gueris[] = $data['gueris'];

                    break;
                }
            }
        }

        //On passe les données à Chart.js
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            //On inverse le tableau, afin d'avoir une lecture de date normal 13,14,...19
            'labels' => array_reverse($label),
            'datasets' => [
                [
                    'label' => 'Hospitalisations',
                    'borderColor' => 'rgb(240, 173, 78)',
                    'data' => array_reverse($hospitalises),
                ],
                [
                    'label' => 'Nouvelles entrées en hospitalisation',
                    'borderColor' => 'rgb(250, 100, 0)',
                    'data' => array_reverse($nouvellesHospitalisations),
                    'hidden' => 'true',
                ],
                [
                    'label' => 'Réeanimations',
                    'borderColor' => 'rgb(217, 83, 79)',
                    'data' => array_reverse($reanimation),
                ],
                [
                    'label' => 'Nouvelles entrées en réeanimation',
                    'borderColor' => 'rgb(200, 0, 0)',
                    'data' => array_reverse($nouvellesReanimations),
                    'hidden' => 'true',
                ],
                [
                    'label' => 'Décès',
                    'borderColor' => 'rgb(41, 43, 44)',
                    'data' => array_reverse($deces),
                    'hidden' => 'true',
                ],
                [
                    'label' => 'Guéris',
                    'borderColor' => 'rgb(92, 184, 92)',
                    'data' => array_reverse($gueris),
                    'hidden' => 'true',
                ],
            ],
        ]);

        $chart->setOptions([/* ... */]);

        //dd($callApiService->getDepartmentData($department));
        return $this->render('department/index.html.twig', [
            'data' => $callApiService->getDepartmentData($department),
            'chart' => $chart,
        ]);
    }
}
