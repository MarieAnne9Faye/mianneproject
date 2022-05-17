<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    
    #[Route('/lists', name: 'list_user')]
    public function listUser(Request $request, EntityManagerInterface $em, PaginatorInterface $paginator ): Response
    {
        
        $dbusers = $em->getRepository(Users::class)->findAll();
        $pagination = $paginator->paginate(
        $dbusers,
        $request->query->getInt('page', 1),
        10
        );
        //dd($dbusers);
        return $this->render('pages/list_users.html.twig', [

            'users' => $pagination,
            //'controller_name' => 'MainController',
        ]);
    }

    #[Route('/ajout', name: 'ajouter')]
    public function ajoutUser(): Response
    {
        return $this->render('pages/add.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/delete/{id}', name: 'supprimer')]
    public function supUser($id, EntityManagerInterface $em) : Response
    {
        $dbuser = $em->getRepository(Users::class)->find($id);
        $em->remove($dbuser);
        $em->flush();

        $dbusers = $em->getRepository(Users::class)->findAll();
        //dd($dbuser);
        return $this->render('pages/list_users.html.twig', 
        [
            'users' => $dbusers,
        ]);
    }

    #[Route('/add', name: 'add_user')]
    public function add_user(Request $request, EntityManagerInterface $em): Response
    {
        $user = new Users();
        $data = $request->request->all();

        $user->setPrenom($data['prenom']);
        $user->setNom($data['nom']);
        $user->setAge($data['age']);
        $user->setClasse($data['classe']);
        
        $em->persist($user);
        $em->flush();
       // $this->addFlash('success', 'Enregistrement fait avec succes');
        return $this->redirectToRoute('ajouter');

    }
}

