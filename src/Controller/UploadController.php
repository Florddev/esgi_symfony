<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Upload;
use App\Repository\UploadRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[IsGranted('ROLE_ADMIN')]
class UploadController extends AbstractController
{
    #[Route(path: '/upload', name: 'upload')]
    public function upload(UploadRepository $uploadRepository): Response
    {
        return $this->render('other/upload.html.twig', [
            'uploads' => $uploadRepository->findAll()
        ]);
    }

    #[Route(path: '/api/upload', name: 'api_upload')]
    public function uploadApi(
        Request $request,
        FileUploader $fileUploader,
        EntityManagerInterface $entityManager
    ): Response {
        /** @var UploadedFile[] $files */
        $files = $request->files->all()['files'];

        foreach ($files as $file) {
            $fileName = $fileUploader->upload($file);
            $upload = new Upload();
            $upload->setUploadedBy($this->getUser());
            $upload->setUrl($fileName);
            $entityManager->persist($upload);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Upload successful!',
        ]);
    }
}