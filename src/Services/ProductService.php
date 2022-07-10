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

    public function prepareJson(array $listJson, array $treeJson) : array
    {
        foreach ($treeJson as $keyT => $tree) {
            foreach ($listJson as $list) {
                if ($tree['id'] == $list['category_id']) {
                    unset($treeJson[$keyT]['children']);
                    $treeJson[$keyT]['name'] = $list['translations']['pl_PL']['name'];
                    $treeJson[$keyT]['children'] = $tree['children'];
                }
                foreach ($tree['children'] as $keyC => $treeChildren) {
                    if ($treeChildren['id'] == $list['category_id']) {
                        unset($treeJson[$keyT]['children'][$keyC]['children']);
                        $treeChildren['name'] = $list['translations']['pl_PL']['name'];
                        $treeJson[$keyT]['children'][$keyC]['name'] = $treeChildren['name'];
                        $treeJson[$keyT]['children'][$keyC]['children'] = [];
                    }
                }
            }
        }
        return $treeJson;
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