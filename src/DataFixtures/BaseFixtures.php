<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\DataFixtures;


use App\Entity\Category;
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
            $category->setTitle('Категория №' . ($i + 1));
            $manager->persist($category);

            $categories[] = $category;
        }

        for ($i = 0; $i < 3; $i++) {
            $material = new Material();
            $material->setTitle('Публикация № ' . ($i + 1) . ' в категории № ' . $categories[0]->getId());
            $material->setCategory($categories[0]);
            $manager->persist($material);
        }
        for ($i = 0; $i < 3; $i++) {
            $material = new Material();
            $material->setTitle('Публикация № ' . ($i + 1) . ' в категории № ' . $categories[1]->getId());
            $material->setCategory($categories[1]);
            if ($i == 0) {
                $material->setImage('https://images.pexels.com/photos/459225/pexels-photo-459225.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940');
            }
            $manager->persist($material);
        }

        $material = new Material();
        $material->setTitle('Публикация № 1 в категории № ' . $categories[2]->getId());
        $manager->persist($material);

        $material = new Material();
        $material->setTitle('Публикация № 1 в категории № ' . $categories[3]->getId());
        $material->setImage('https://images.pexels.com/photos/248797/pexels-photo-248797.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940');
        $manager->persist($material);

        $manager->flush();
    }
}