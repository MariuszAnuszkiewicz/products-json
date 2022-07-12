<?php

namespace App\Services;

class ProductService
{
    public function readJson(string $readDirectory) : array
    {
        if (is_dir($readDirectory)) {
            if ($dh = opendir($readDirectory)) {
                while (($file = readdir($dh)) !== false) {
                    $listJson = json_decode(file_get_contents($readDirectory . DIRECTORY_SEPARATOR . 'list.json'), true);
                    $treeJson = json_decode(file_get_contents($readDirectory . DIRECTORY_SEPARATOR . 'tree.json'), true);
                }
                closedir($dh);
            }
        }
        return ['list' => $listJson, 'tree' => $treeJson];
    }

    public function prepareJson(array $treeJson, array $listJson)
    {
        function showTree($treeJson, $listJson) {
            $data = [];
            if (is_array($treeJson) && count($treeJson) > 0) {
                foreach ($treeJson as $key => $treeValue) {
                    if (is_array($treeValue)) {
                        $data['children'][] = showTree($treeValue, $listJson);
                    } else {
                        $listKey = array_search($treeValue, array_column($listJson, 'category_id'));
                        $categoryId = (int) $listJson[$listKey]['category_id'];
                        if ($treeValue == $categoryId) {
                            $data['id'] = $treeValue;
                            $data['name'] = $listJson[$listKey]['translations']['pl_PL']['name'];
                        }
                    }
                }
            }
            return $data;
        }
        return showTree($treeJson, $listJson);
    }


    public function writeJson(array $treeFile, string $writeDirectory)
    {
        if (is_dir($writeDirectory)) {
            if ($dh = opendir($writeDirectory)) {
                while (($file = readdir($dh)) !== false) {
                    $modifyContent = json_encode($treeFile, JSON_PRETTY_PRINT);
                    file_put_contents($writeDirectory . DIRECTORY_SEPARATOR . 'tree_modify.json', $modifyContent);
                }
                closedir($dh);
            }
        }
    }

    public function eraseJson(string $eraseDirectory)
    {
        if (is_dir($eraseDirectory)) {
            if ($dh = opendir($eraseDirectory)) {
                while (($file = readdir($dh)) !== false) {
                    file_put_contents($eraseDirectory . DIRECTORY_SEPARATOR . 'tree_modify.json', []);
                }
                closedir($dh);
            }
        }
    }
}