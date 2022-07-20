<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;

class APIController extends Controller
{

    public function token(){

        try {
            //Criação do objeto cliente
            $guzzle = new Client([

                'headers' => [
                    'gw-dev-app-key' => config('apiCobranca.gw_dev_app_key'),
                    'Authorization' => config('apiCobranca.authorization'),
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                //Desativar SSL
                'verify' => false
            ]);
            //Requisição POST
            $response = $guzzle->request('POST', 'https://oauth.sandbox.bb.com.br/oauth/token?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key'),
                array(
                    'form_params' => array(
                        'grant_type' => 'client_credentials',
                        'client_id' => config('apiCobranca.client_id'),
                        'client_secret' => config('apiCobranca.client_secret'),
                        'scope' => 'cobrancas.boletos-info cobrancas.boletos-requisicao'
                    )));

            //Recuperar o corpo da resposta da requisição
            $body = $response->getBody();

            //Acessar as dados da resposta - JSON
            $contents = $body->getContents();

            //Converter o JSON em array associativo PHP
            $token = json_decode($contents);

            return $token->access_token;

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function registrar(){
        //Informações do boleto
        $body = array(
            'numeroConvenio' => 3128557,
            'numeroCarteira' => 17,
            'numeroVariacaoCarteira' => 35,
            'codigoModalidade' => 1,
            'dataEmissao' => '20.07.2022',
            'dataVencimento' => '01.08.2022',
            'valorOriginal' => 100.00,
            'valorAbatimento' => 0,
            'quantidadeDiasProtesto' => 0,
            'indicadorNumeroDiasLimiteRecebimento' => 'N',
            'numeroDiasLimiteRecebimento' => 0,
            'codigoAceite' => 'A',
            'codigoTipoTitulo' => 4,
            'descricaoTipoTitulo' => 'DS',
            'indicadorPermissaoRecebimentoParcial' => 'N',
            'numeroTituloBeneficiario' => '000101',
            'textoCampoUtilizacaoBeneficiario' => 'TESTE',
            'codigoTipoCaucao' => 0,
            'numeroTituloCliente' => '000312855799999901001',
            'textoMensagemBloquetoOcorrencia' => 'TESTE',
            'pagador' => array(
                    'tipoRegistro' => 1,
                    'numeroRegistro' => 71128590182,
                    'nome' => 'Teste',
                    'endereco' => 'Endereco',
                    'cep' => 70675727,
                    'cidade' => 'Caxias do Sul',
                    'bairro' => 'Centro',
                    'uf' => 'SP',
                    'telefone' => '999999999'
            ),
            'email' => 'cliente@email.com'
        );

        //Converte array em JSON
        $body = json_encode($body);
        // dd($body);
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json',
                ],
                'verify' => false
            ]);

            //Requisição
            $response = $guzzle->request('POST', 'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key'),
                [
                    'body' => $body
                ]
            );

        //Recuperar o corpo da resposta da requisição
        $body = $response->getBody();

        //Acessar os dados da resposta - JSON
        $contents = $body->getContents();

        //Converter o JSON em array associativo PHP
        $boleto = json_decode($contents);

        dd($boleto);

        } catch (ClientException $e) {
            echo $e->getMessage();
        }
    }

    public function listar(){
        try {
            $guzzle = new Client([
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->token(),
                    'Content-Type' => 'application/json',
                ],
                'verify' => false
            ]);

            //Requisição
            $response = $guzzle->request('GET', 'https://api.hm.bb.com.br/cobrancas/v1/boletos?gw-dev-app-key='. config('apiCobranca.gw_dev_app_key') .
                '&agenciaBeneficiario=' . '452' .
                '&contaBeneficiario=' . '123873' .
                '&indicadorSituacao=' . 'B' .
                '&indice=' . '300' .
                '&codigoEstadoTituloCobranca=' . '7' .
                '&dataInicioMovimento=' . '01.01.2022' .
                '&dataFimMovimento=' . '30.07.2022'
            );

            //Recuperar o corpo da resposta da requisição
            $body = $response->getBody();

            //Acessar os dadoss da resposta - JSON
            $contents = $body->getContents();

            //Coverter JSON em array associativo PHP
            $boletos = json_decode($contents);

            dd($boletos);

        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    public function consultar(){
        dd('teste api');
    }

    public function baixar(){
        dd('teste api');
    }

    public function atualizar(){
        dd('teste api');
    }
}
