<?php
namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\Result;
use App\Dto\ResultOutput;
use Symfony\Component\VarDumper\VarDumper;

class ResultOutputDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($data, string $to, array $context = [])
    {
        //VarDumper::dump($data);
        $output = new ResultOutput();
//         $output->name = $data->name;
        return $output;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
//         VarDumper::dump($data);
//         VarDumper::dump($to);
        //exit;
        return ResultOutput::class === $to && $data instanceof Result;
    }
}