<?php

namespace App\Controller;

use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product", name="product_")
 */
class ProductController extends AbstractController
{
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @Route("/write", name="write", methods={"GET"})
     */
    public function write(): Response
    {
        $files = $this->productService->readJson($this->getParameter('json_files_location'));
        $modifyFile = $this->productService->prepareJson($files['list'], $files['tree']);
        $this->productService->writeJson($modifyFile, $this->getParameter('json_file_output_location'));
        return new Response('file has been modify');
    }

    /**
     * @Route("/erase", name="erase", methods={"GET"})
     */
    public function erase(): Response
    {
        $this->productService->eraseJson($this->getParameter('json_file_output_location'));
        return new Response('file content has been erase');
    }
}
