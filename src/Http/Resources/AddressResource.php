<?php

namespace Sankhya\Http\Resources;

use Saloon\Http\Response;
use Sankhya\Contracts\ResourceContract;
use Sankhya\Http\Requests\ExecuteQueryRequest;
use Sankhya\Http\Requests\LoadRecordsRequest;

class AddressResource extends Resource implements ResourceContract
{

    public string $entity = 'Endereco';

    public string $primaryKey = 'CODEND';

    public function postcode(string $postcode): Response
    {
        return $this->connector->send(
            new ExecuteQueryRequest("SELECT TSICEP.CEP, TSIEND.CODEND, TSIEND.NOMEEND, TFPLGR.CODLOGRADOURO, TFPLGR.DESCRLOGRADOURO, TSIBAI.CODBAI, TSIBAI.NOMEBAI, TSICID.CODCID, TSICID.NOMECID, TSIUFS.CODUF, TSIUFS.UF
                                         FROM TSICEP
                                             INNER JOIN TSIEND ON (TSIEND.CODEND = TSICEP.CODEND)
                                             INNER JOIN TFPLGR ON (TFPLGR.CODLOGRADOURO = TSIEND.CODLOGRADOURO)
                                             INNER JOIN TSIBAI ON (TSIBAI.CODBAI = TSICEP.CODBAI)
                                             INNER JOIN TSICID ON (TSICID.CODCID = TSICEP.CODCID)
                                             INNER JOIN TSIUFS ON (TSIUFS.CODUF = TSICID.UF)
                                         WHERE TSICEP.CEP LIKE '$postcode'")
        );
    }

    public function search(string $address): Response
    {
        $address = mb_convert_case($address, MB_CASE_UPPER, 'UTF-8');

        $search = implode(
            ', ',
            array_map(fn ($item) => "'" . $item . "'", [
                preg_replace(array("/(Á|À|Ã|Â|Ä)/", "/(É|È|Ê|Ë)/", "/(Í|Ì|Î|Ï)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(Ú|Ù|Û|Ü)/", "/(Ñ)/", "/(Ç)/"), explode(" ", "A E I O U N C"), $address),
                $address
            ])
        );

        return $this->connector->send(
            new ExecuteQueryRequest("SELECT * FROM TSIEND INNER JOIN TFPLGR
                                  ON (TFPLGR.CODLOGRADOURO = TSIEND.CODLOGRADOURO)
                                  WHERE ((UPPER(CONCAT(TFPLGR.DESCRLOGRADOURO, CONCAT(' ', NOMEEND))) IN ($search) )
                                    OR (UPPER(CONCAT(TFPLGR.CODLOGRADOURO, CONCAT(' ', NOMEEND))) IN ($search) )
                                    OR (UPPER(CONCAT(TFPLGR.DESCRLOGRADOURO, CONCAT(' ', DESCRICAOCORREIO))) IN ($search) )
                                    OR (UPPER(CONCAT(TFPLGR.CODLOGRADOURO, CONCAT(' ', DESCRICAOCORREIO))) IN ($search) )
                                    OR (UPPER(DESCRICAOCORREIO) IN ($search) )
                                    OR (UPPER(NOMEEND) IN ($search) ))")
        );


    }
}
