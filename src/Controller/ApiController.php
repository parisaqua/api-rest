<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    /**
     * @Route("/listeRegions", name="listeRegions")
     */
    public function listeRegions(SerializerInterface $serializer)
    {
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions=$serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');
       
        return $this->render('api/index.html.twig', [
           'mesRegions' => $mesRegions
        ]);
    }

        /**
     * @Route("/listeDepsParRegion", name="listeDepsParRegion")
     */
    public function listeDepsParRegion(Request $request, SerializerInterface $serializer)
    {
         // je récupère la région selectionnée dans le formulaire
         $codeRegion=$request->query->get('region');

        // je récupère les régions
        $mesRegions=file_get_contents('https://geo.api.gouv.fr/regions');
        $mesRegions=$serializer->deserialize($mesRegions, 'App\Entity\Region[]', 'json');

       // je récupère les départements
        if($codeRegion == null || $codeRegion == "Toutes") {
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/departements');
        }
        else {
            $mesDeps=file_get_contents('https://geo.api.gouv.fr/regions/'.$codeRegion.'/departements');
        }
        // décodage du json en tableau
        $mesDeps=$serializer->decode($mesDeps, 'json');
       
        return $this->render('api/listDepsParRegion.html.twig', [
           'mesRegions' => $mesRegions,
           'mesDeps' => $mesDeps
        ]);
    }



}
