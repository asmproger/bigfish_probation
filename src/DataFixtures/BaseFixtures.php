<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Material;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BaseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setEmail('asmproger@gmail.com');
        $admin->setUsername('admin');
        $admin->setPlainPassword('123456');
        $admin->setEnabled(true);
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $categories = [];
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setTitle('Категория_' . mt_rand(10, 100));
            $manager->persist($category);

            $categories[] = $category;
        }
        $manager->flush();

        for ($i = 0; $i < 3; $i++) {
            $material = new Material();
            $material->setTitle('Публикация_' . mt_rand(10, 100));
            $material->setCategory($categories[0]);
            $material->setContent('First category ' . $i .' material content');
            $manager->persist($material);
        }
        for ($i = 0; $i < 3; $i++) {
            $material = new Material();
            $material->setTitle('Публикация_' . mt_rand(10, 100));
            $material->setCategory($categories[1]);
            $material->setContent('Second category ' . $i .' material content');
            if ($i == 0) {
                $image = new Image();
                $image->setPath('images/test1.jpeg');
                $image->setMaterial($material);
                $manager->persist($image);;
            }
            $manager->persist($material);
        }

        $material = new Material();
        $material->setTitle('Публикация_' . mt_rand(10, 100));
        $material->setCategory($categories[2]);
        $material->setContent('Third category material content');
        $manager->persist($material);

        $material = new Material();
        $material->setTitle('Публикация_' . mt_rand(10, 100));
        $material->setCategory($categories[3]);
        $material->setContent('Fourth category material content');

        $image = new Image();
        $image->setPath('images/test2.jpeg');
        $image->setMaterial($material);
        $manager->persist($image);

        $manager->persist($material);

        $manager->flush();
    }
}