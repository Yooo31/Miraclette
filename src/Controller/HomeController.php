<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'home.')]
// #[IsGranted('IS_AUTHENTICATED_FULLY')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(Request $request): Response
    {
        // Get current username and ROLE
        // $user = $this->getUser();
        // $username = $user->getUsername();
        // $role = $user->getRoles()[0]; // Assuming only one role per user

        // if ($role === 'ROLE_ADMIN') {
        //     return $this->render('home/admin/index.html.twig', [
        //         'username' => $username,
        //         'role' => $role,
        //     ]);
        // }

        if ($request->isMethod('POST')) {
            $file = $request->files->get('file');

            if ($file && $file->getClientOriginalExtension() === 'pdf') {
                $tempDir = $this->getParameter('kernel.project_dir') . '/temp';
                $tempFilePath = $tempDir . '/menu-casa-catherina.pdf';

                // Assure-toi que le dossier temporaire existe
                if (!is_dir($tempDir)) {
                    mkdir($tempDir, 0777, true);
                }

                try {
                    // Sauvegarde le fichier dans le répertoire temporaire
                    $file->move($tempDir, 'menu-casa-catherina.pdf');
                    $this->addFlash('success', 'Fichier téléchargé avec succès!');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du fichier.' . $e->getMessage());
                }
            } else {
                $this->addFlash('error', 'Seuls les fichiers PDF sont acceptés.');
            }

            return $this->redirectToRoute('home.index');
        }

        return $this->render('home/index.html.twig');
    }

}
