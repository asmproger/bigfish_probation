<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\EventSubscriber;


use App\Entity\Material;
use App\Services\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Subscriber implements EventSubscriberInterface
{

    /**
     * @var FileUploader
     */
    private $fileUploader;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Subscriber constructor.
     * @param FileUploader           $fileUploader
     */
    public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager, LoggerInterface $log)
    {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
        $this->log = $log;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'easy_admin.pre_persist' => ['processImage'],
            'easy_admin.pre_update' => ['processImage'],
        ];
    }

    /**
     * @param GenericEvent $event
     */
    public function processImage(GenericEvent $event): void
    {
        $item = $event->getSubject();
        if (!($item instanceof Material)) {
            return;
        }

        $imageFile = $item->getImageFile();
        if ($imageFile instanceof UploadedFile) {
            $image = $this->fileUploader->uploadImage($imageFile, $item->getImage());
            $item->setImage($image);
            $image->setMaterial($item);

            $event['entity'] = $item;
            $this->entityManager->persist($image);
            $this->entityManager->persist($item);
        }
    }
}