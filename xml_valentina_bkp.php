<?php
/** VERSÃO 1.0 - 14 ABRIL 2015
 * Changelog:
 *
 * 11/04/15
 * - Substituição de "," por "." no peso.
 *
 * 14/04/15
 * - As tags que não tem valor e que não são obrigatórias não devem aparecer no XML;
 * - A tag preço promocional, quando preenchida, exige que as tags Inicio e Fim da promoção estejam preenchidas;
 *- Faltou a tag SKU;

 * */

$ch = curl_init();
//curl_setopt($ch, CURLOPT_URL, 'http://www.theodorahome.com.br/XMLProdutos.asp?IDLoja=19925');

curl_setopt($ch, CURLOPT_URL, 'http://www.theodorahome.com.br/XMLProdutos.asp?IDLoja=19925&Format=3&Type=XML&Arq=XMLPers.htm');

curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

function h($s)
{
    return htmlspecialchars($s);
}

function escapeXmlValue($value)
{
    return is_null($value) ? null : h($value);
}


function categoriasToMateriais($categoria)
{
    switch ($categoria) {


        case "Bambu":
            $categoriaIlove = '16';
            break;

        case "Cerâmicas":
            $categoriaIlove = '4';
            break;

        case "Porcelanas":
            $categoriaIlove = '22';
            break;

        default:
            $categoriaIlove = '';
    }

    return $categoriaIlove;
}


function categoriasToAmbientes($categoria)
{
    switch ($categoria) {
        /*Rouparia*/
        case "Banho":
            $categoriaIlove = array(2);;
            break;

        case "Lababo":

            $categoriaIlove = array(15);;
            break;

        case "Mantas":
            $categoriaIlove = array(19, 21, 29);
            break;

        case "Bandejas":
        case "Bambu":
        case "Cerâmicas":
        case "Copos e Taças":
        case "Baldes de Gelo":
        case "Guardanapos":
        case "Jogo Americano":
        case "Porcelanas":
        case "Porta Guardanapos":
        case "Prataria":
        case "Sousplats":
        case "Talheres":
        case "Outros":
            $categoriaIlove = array(23, 22, 7, 8, 5);
            break;


        /*Decor*/
        case "Abajures e Luminárias":
        case "Almofadas":
        case "Espelhos e Quadros":
        case "Livros":
        case "Muranos":
        case "Objetos":
        case "Porta-Retratos":
        case "Porta Velas":
        case "Vasos":
            $categoriaIlove = array(1, 3, 4, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25);

            break;


        /*Moveis*/
        case "Móveis":
        case "Garden Seat":
        case "Mesa de Apoio":
        case "Outros":
            $categoriaIlove = array(7, 8, 14, 17, 18, 19, 20, 21, 22, 23, 25, 25);
            break;

        /*Acessorios*/
        case "Papelaria":
        case "Puxadores":
            $categoriaIlove = array(21, 20, 19, 17, 13, 11, 9, 4, 3);
            break;

        /*Aromas*/
        case "Aromas":
        case "Presentes":
        case "Custom Made":
            $categoriaIlove = array(9);
            break;


        case "Baby":
            $categoriaIlove = array(17);
            break;
        default:
            $categoriaIlove = array('');//Casa & Decor
    }

    return $categoriaIlove;
}


function addProduto($document, $nome, $designer, $codigo, $sku, $descricao,
                    $video, $largura, $altura, $profundidade,
                    $peso, $largura_emb, $altura_emb, $profundidade_emb, $peso_emb, $estoque, $sob_encomenda,
                    $correios_entrega, $preco, $preco_promo, $promo_inicio, $promo_fim,
                    $ambientes, $ambiente, $locais, $local, $materiais, $material, $cores, $cor,
                    $fotos, $foto)
{
    #criar produto
    $produto = $document->createElement("produto");
    $nome = $document->createElement("nome", escapeXmlValue($nome));
    $designer = $document->createElement("designer", $designer);
    $codigo = $document->createElement("codigo", $codigo);
    $sku = $document->createElement("sku", $sku);
    $descricao = $document->createElement("descricao", escapeXmlValue($descricao));
    $video = $document->createElement("video", escapeXmlValue($video));
    $largura = $document->createElement("largura", $largura);
    $altura = $document->createElement("altura", $altura);
    $profundidade = $document->createElement("profundidade", $profundidade);
    $peso = $document->createElement("peso", $peso);
    $largura_emb = $document->createElement("largura_emb", $largura_emb);
    $altura_emb = $document->createElement("altura_emb", $altura_emb);
    $profundidade_emb = $document->createElement("profundidade_emb", $profundidade_emb);
    $peso_emb = $document->createElement("peso_emb", $peso_emb);
    $estoque = $document->createElement("estoque", $estoque);
    $sob_encomenda = $document->createElement("sob_encomenda", $sob_encomenda);
    $correios_entrega = $document->createElement("correios_entrega", $correios_entrega);
    $preco = $document->createElement("preco", $preco);
    $preco_promo = $document->createElement("preco_promo", $preco_promo);
    $promo_inicio = $document->createElement("promo_inicio", $promo_inicio);
    $promo_fim = $document->createElement("promo_fim", $promo_fim);
    $ambientes = $document->createElement("ambientes", $ambientes);

    $arrayambientes = categoriasToAmbientes($ambiente);

    $tamAmbientes = count($arrayambientes);
//echo '<p>'.$tamAmbientes.'</p>';
//print_r($arrayambientes);
    $ambiente_novo = array();
    $i = 0;
    for ($i = 0; $i < $tamAmbientes; $i++) {

        $ambiente_novo[$i] = $document->createElement("ambiente", $arrayambientes[$i]);

    }
    // print_r($ambiente_novo);

    $locais = $document->createElement("locais", $locais);
    $local = $document->createElement("local", $local);
    $materiais = $document->createElement("materiais", $materiais);
    $material = $document->createElement("material", categoriasToMateriais($material));
    $cores = $document->createElement("cores", $cores);
    $cor = $document->createElement("cor", $cor);
    $fotos = $document->createElement("fotos", $fotos);

    $foto = $document->createElement("foto", $foto);


    $produto->appendChild($nome);
    //$produto->appendChild($designer);
    $produto->appendChild($codigo);
    $produto->appendChild($sku);
    $produto->appendChild($descricao);
    //$produto->appendChild($video);
    $produto->appendChild($largura);
    $produto->appendChild($altura);
    $produto->appendChild($profundidade);

    $produto->appendChild($peso);
    $produto->appendChild($largura_emb);
    $produto->appendChild($altura_emb);
    $produto->appendChild($profundidade_emb);
    $produto->appendChild($peso_emb);

    $produto->appendChild($estoque);
    $produto->appendChild($sob_encomenda);
    $produto->appendChild($correios_entrega);
    $produto->appendChild($preco);


    if (strlen($promo_inicio->nodeValue)>2) {
        $produto->appendChild($preco_promo);
        $produto->appendChild($promo_inicio);
        $produto->appendChild($promo_fim);
    }

    $produto->appendChild($ambientes);


    for ($i = 0; $i < $tamAmbientes; $i++) {
        $ambientes->appendChild($ambiente_novo[$i]);

    }


    //$produto->appendChild($locais);
    //$locais->appendChild($local);
    if (strlen($material->nodeValue)>0) {
    $produto->appendChild($materiais);
    $materiais->appendChild($material);
    }

    //$produto->appendChild($cores);
   // $cores->appendChild($cor);

    $produto->appendChild($fotos);
    $fotos->appendChild($foto);


    return $produto;
}


$dom = new DOMDocument("1.0", "utf-8");
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;


$content = curl_exec($ch);
curl_close($ch);
//$dom = simplexml_load_string($content);


//echo $content;


$root = $dom->createElement("produtos");


if ($myxml = simplexml_load_string($content)) {
    foreach ($myxml as $post) {
        $nome = '<![CDATA[' . $post->nome . ']]>';
        $designer = '';
        $codigo = $post->codigo;
        $sku  = $post->codigo;
        $descricao = '<![CDATA[' . $post->descricao . ']]>';

        if (strlen($post->video) > 2) {
            $video = '<![CDATA[' . $post->video . ']]>';
        } else {
            $video = '';
        }
        $largura = '1';
        $altura = '1';
        $profundidade = '1';
        $peso_float = str_replace(" kg", "", $post->peso);
        $peso_float = str_replace(",", ".", $peso_float);

        $peso = $peso_float;
        $largura_emb = '1';
        $altura_emb = '1';
        $profundidade_emb = '1';


        $peso_emb = $peso_float;
        $estoque = $post->estoque;
        $sob_encomenda = 'false';
        $correios_entrega = 'true';
        $preco = $post->preco;


        $preco_promo = $post->preco_promo;
        $promo_inicio = $post->promo_inicio;
        $promo_fim = $post->promo_fim;


        $ambientes = '';
        $ambiente = $post->cat;

        $locais = '';
        $local = '';

        $materiais = '';
        $material = $post->cat;

        $cores = '';
        $cor = '';

        $fotos = '';
        $foto = '<![CDATA[' . $post->fotos . ']]>';


        $prod = addProduto($dom, $nome, $designer, $codigo, $sku, $descricao,
            $video, $largura, $altura, $profundidade,
            $peso, $largura_emb, $altura_emb, $profundidade_emb, $peso_emb, $estoque, $sob_encomenda,
            $correios_entrega, $preco, $preco_promo, $promo_inicio, $promo_fim,
            $ambientes, $ambiente, $locais, $local, $materiais, $material, $cores, $cor,
            $fotos, $foto
        );

        $root->appendChild($prod);
    }
} else {
    echo 'Erro ao ler ficheiro XML';
}


#adicionando no root
$dom->appendChild($root);

#salvando o arquivo
$dom->save("vitrineValentina.xml");

#mostrar dados na tela
header("Content-Type: text/xml");
print $dom->saveXML();

