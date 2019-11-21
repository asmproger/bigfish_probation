<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\Controller;


use App\Entity\Category;
use App\Entity\Material;
use App\Services\CommandParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 * @package App\Controller
 *
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ApiController constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/get-data", name="api_get_data", methods={"GET"})
     * @param Request                $request
     * @param EntityManagerInterface $entityManager
     * @param CommandParser          $commandParser
     * @return Response
     */
    public function getData(
        Request $request,
        EntityManagerInterface $entityManager,
        CommandParser $commandParser
    ) {
        /*
         * у нас на входе произволььная строка
         * и может быть две команды, одна - с параметром
         * 0. убираем лишние пробелы, приводим в нижний регистр, убираемм слэш
         * 1. определяем команду через поиск подстроки
         * 2. по результату - либо сразу определяем валидность команды(есть только искомая подстрока)
         * 3. либо извлекаем параметр (ищем шаблон с №) и обрезаем его
         * 4. либо, если его нет (а должен быть) - говорим, что команда неверная
         * */
        $command = $request->get('command');
        $result = $commandParser->parseCommand($command);

        if (!$command || !$result || !$result['valid']) {
            return $this->json([
                'status' => true,
                //@todo translations?
                'message' => 'команда неверная',
            ]);
        }

        $method = $result['commandParams']['code'];

        if ($result['commandParams']['hasParameter']) {
            $response = $this->$method($result['parameter']);
        } else {
            $response = $this->$method();
        }

        return $this->json($response);
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $list = $categoryRepository->findAll();
        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle() . ' № ' . $item->getId(),
            ];
        }

        return $data ?
            [
                'status' => true,
                'data' => $data,
            ] :
            [
                'status' => true,
                'message' => 'категории отсутствуют',
            ];
    }

    /**
     * @param string $categoryId
     * @return array
     */
    public function getMaterials(string $categoryId): array
    {
        $materialRepository = $this->entityManager->getRepository(Material::class);
        $list = $materialRepository->findBy([
            'category' => $categoryId,
        ]);

        $data = [];
        foreach ($list as $item) {
            $data[] = [
                'id' => $item->getId(),
                'title' => $item->getTitle() . ' № ' . $item->getId() . ' в категории № ' . $categoryId,
                'image' => $item->getImage() ? $item->getImage()->getPath() : null,
            ];
        }

        return $data ?
            [
                'status' => true,
                'data' => $data,
            ] :
            [
                'status' => true,
                'message' => "публикации по категории {$categoryId} отсутствуют",
            ];
    }

    /**
     * @param string $materialId
     * @return array
     */
    public function getMaterial(string $materialId): array
    {
        $materialRepository = $this->entityManager->getRepository(Material::class);
        $item = $materialRepository->find($materialId);

        return $item ?
            [
                'status' => true,
                'content' => $item->getContent(),
                'image' => $item->getImage()->getPath(),
            ] :
            [
                'status' => true,
                'message' => 'публикация не найдена',
            ];
    }
}