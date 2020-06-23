<?php

declare(strict_types=1);

namespace App\Plugin\Core\Libraries\Composer;

use Illuminate\Support\Arr;
use InvalidArgumentException;

class Parser
{
    private $params = [];

    private $paramsRaw = [];

    public function __construct(string $composerFile)
    {
        if (is_file($composerFile) === false) {
            throw new InvalidArgumentException("O arquivo {$composerFile} não existe");
        }

        $params = @json_decode(file_get_contents($composerFile), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("O arquivo {$composerFile} está corrompido");
        }

        if (isset($params['name']) === false || isset($params['description']) === false) {
            throw new InvalidArgumentException("O arquivo {$composerFile} é inválido");
        }

        $this->parse($params);
    }

    private function parse(array $params)
    {
        $this->paramsRaw = $params;

        $params = Arr::dot($params);
        $fix = fn($value, $key) => 
            $this->params[trim(mb_strtolower(str_replace(['\\','-'], '_',$key)), '_')] = $value;
        array_walk($params, $fix);
    }

    /**
      * Obtem o valor de um parâmetro de configuração existente no composer.json.
      * A busca deve ser feita usando a notação pontuada do Laravel.
      * Ex.: nome.param.subparam
      */
    public function param(?string $name)
    {
        return $this->params[$name] ?? null;
    }

    /**
      * Obtem todos os parâmetros de configuração do composer.json
      * 
      * @return array
      */
      public function all($raw = false) : array
      {
          if ($raw === true) {
            return $this->paramsRaw;
          }
          
          return $this->params;
      }
}
