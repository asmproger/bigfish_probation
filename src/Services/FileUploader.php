<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\Services;


use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FileUploader
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 * @package App\Services
 */
class FileUploader
{

    /**
     * @var KernelInterface
     */
    private $kernelInterface;

    /**
     * FileUploader constructor.
     * @param KernelInterface        $kernelInterface
     */
    public function __construct(KernelInterface $kernelInterface)
    {
        $this->kernelInterface = $kernelInterface;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param Image|null   $image
     * @return Image
     */
    public function uploadImage(UploadedFile $uploadedFile, Image $image = null): Image
    {
        $path = '/images/';
        $dir = $this->kernelInterface->getProjectDir() . '/public' . $path;
        $name = substr(md5(time()), 0, 10) . '.' . $uploadedFile->guessExtension();
        $uploadedFile->move($dir, $name);

        $image = $image ? $image : new Image();
        $image->setPath($path . $name);

        return $image;
    }
}