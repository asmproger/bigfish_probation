<?php
/**
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 */

namespace App\Services;


/**
 * Class CommandParser
 * @author asmproger <asmproger@gmail.com>
 * @copyright (c) 2019, asmproger
 * @package App\Services
 */
class CommandParser
{
    /**
     * @var array
     */
    protected $_allowedCommands = [
        [
            'command' => '/список категорий',
            'hasParameter' => false,
            'code' => 'getCategories',
        ],
        [
            'command' => '/публикации по категории',
            'hasParameter' => true,
            'code' => 'getMaterials',
        ],
    ];

    /**
     * @param string $command
     * @return array
     */
    public function parseCommand(string $command): array
    {
        $command = $this->prepareCommand($command);
        $response = [
            'valid' => false,
            'parameter' => 0,
            'commandParams' => [],
        ];

        $searchResult = array_filter($this->_allowedCommands, function (array $item) use ($command) {
            return strpos($command, $item['command']) === 0;
        });

        if (count($searchResult) != 1) {
            return $response;
        }

        $searchResult = array_shift($searchResult);
        $response['commandParams'] = $searchResult;
        if (!$searchResult['hasParameter']) {
            $response['valid'] = $searchResult['command'] == $command;
        } else {
            $response['parameter'] = $this->extractParameter($command);
            $response['valid'] = ($searchResult['command'] == $command) && $response['parameter'];
        }

        return $response;
    }

    /**
     * @param string $raw
     * @param string $desire
     * @return bool
     */
    public function isValidCommand(string $raw, string $desire): bool
    {
        $command = $this->prepareCommand($raw);

        return in_array($command, $this->_commandsCodes[$desire]);
    }

    /**
     * @param string $raw
     * @param string $desire
     * @return array
     */
    public function isValidCommandWithParam(string $raw, string $desire): array
    {
        $command = $this->prepareCommand($raw);
        $parameter = $this->extractParameter($command);

        $commandValid = in_array($command, $this->_commandsCodes[$desire]);

        return ($commandValid && $parameter) ?
            [
                'status' => $commandValid,
                'parameter' => $parameter,
            ] :
            [];
    }

    /**
     * @param string $command
     * @return string|null
     */
    protected function extractParameter(string &$command): ?string
    {
        $pattern = '/№\s[0-9]+/';
        $matches = [];
        preg_match_all($pattern, $command, $matches);
        $matches = array_shift($matches);
        switch (count($matches)) {
            case 1:
                $command = trim(preg_replace($pattern, '', $command));
                $parameter = explode(' ', $matches[0]);
                return $parameter[1] ?? null;
                break;
            default:
                return null;
        }
    }

    /**
     * @param string $raw
     * @return string
     */
    protected function prepareCommand(string $raw): string
    {
        $command = mb_strtolower(preg_replace('/\s+/', ' ', $raw));

        return $command;
    }

}