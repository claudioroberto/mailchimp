<?php

namespace Source\Models;

/**
 * MailChimp: Classe de integração com o MailChimp
 * Foi utilizado como modelo a aula ActiveCampaign via API do UpInside Play mostrando
 * sincronismo de contatos.
 * 
 * @author Claudio Roberto <claudiorobertonc@gmail.com>
 * @link https://www.jdyc.com.br/ 
 */
class MailChimp
{

    private $acUrl;
    private $acKey;
    private $action;
    private $output;
    private $callback;
    private $params;

    public function __construct()
    {
        $this->acUrl = "https://xxxx.api.mailchimp.com/3.0";
        $this->acKey = "sua chave";
        $this->output = "json";
    }

    /**
     * getByEmail: Busca um contato pelo e-mail no AC.
     * @param string $email e-mail que deseja consultar
     */
    public function getByEmail($email)
    {
        $this->action = "search-members";
        $this->params = ["query" => $email, "fields" => "exact_matches.members"];
        $this->get();
    }

    /**
     * addActive: Adiciona o contato como ativo a uma ou mais listas em seu AC
     * @param string $firstname Primeiro nome do contato
     * @param string $lastname Sobrenome do contato
     * @param string $email E-mail do contato
     * @param array $listId Vetor com ID AC de listas que vai cadastrar o usuário. [1,5,7]
     * @param string $comaTags Tags separadas por vírgula. Upinside, UpInside Play, ActiveCampaign
     */
    public function addActive($firstname, $lastname, $email, $listId)
    {
        $this->action = "lists/{$listId}/members";
        $mergeFields = '{"FNAME":"'.$firstname.'", "LNAME":"'.$lastname.'"}';
        $this->params = [
            "merge_fields" => $mergeFields,
            "email_address" => $email,
            "status" => "subscribed"
        ];
        $this->post();
    }

    /**
     * getCallback: Retorna os dados de resposta da integração!
     * @return object Objeto de retorno do ActiveCampaign
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * PRIVATE METHODS
     */

    /**
     * Efetua uma comunicação via HTTP GET
     */
    private function get()
    {
        $ch = curl_init();
        $url = "{$this->acUrl}/{$this->action}?" . http_build_query($this->params);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: apikei {$this->acKey}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }

    /**
     * Efetua uma comunicação via HTTP POST
     */
    private function post()
    {
        $ch = curl_init();
        $url = "{$this->acUrl}/{$this->action}";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: apikei {$this->acKey}"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->params));
        $this->callback = json_decode(curl_exec($ch));
        curl_close($ch);
    }

}
