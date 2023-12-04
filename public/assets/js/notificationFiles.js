function notificationFilesSped(pathLogoImg, empresaNotification) {
    event.preventDefault();

    const empNot = empresaNotification;
    console.log(empresaNotification);
    const dataAtual = new Date();
    const dataFormatada = dataAtual.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', year: '2-digit' }).replace(/\//g, '.');

    let htmlRecibo = /*html*/`<!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Notificação</title>
        <style>
            * {
                font-family: "Arial", sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            main {
                width: 720px;
            }

            .cabecalho-container {
                width: 100%;
                height: 180px;
                border: 1pt solid black;
                display: flex;
                flex-direction: row;
            }

            .cabecalho-container .logo {
                width: 18%;
                border-right: 1pt solid black;

                display: flex;
                justify-content: center;
                align-items: center;
            }

            .cabecalho-container .cabecalho {
                width: 52%;
                border-right: 1pt solid black;
                text-align: center;

                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .cabecalho-container .cabecalho .n-prefeitura,
            .cabecalho-container .cabecalho .n-secretaria,
            .cabecalho-container .cabecalho .n-setor {
                font-size: 20px;
            }

            .cabecalho-container .cabecalho .n-prefeitura {
                font-weight: 700;
                padding: 10px;
            }

            .cabecalho-container .cabecalho .n-setor {
                font-weight: 700;
                padding: 10px;
            }

            .cabecalho-container .info {
                width: 30%;
                text-align: center;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .cabecalho-container .info .parag-texto {
                height: 70%;
                font-size: 20px;
                display: flex;
                margin: 6px;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .cabecalho-container .info .parag-texto p.n-destaque {
                font-weight: 700;
                text-decoration: underline;
                font-size: 22px;
                margin-bottom: 6px;
                padding: 8px;
            }

            .cabecalho-container .info .data-notifica {
                width: 100%;
                height: 30%;
                font-size: 22px;
                border-top: 1pt solid black;
                display: flex;
                justify-content: center;
                align-items: center;

            }

            .n-linha-destaque {
                width: 100%;
                font-size: 16px;
                height: 30px;
                border: 1pt solid black;
                font-weight: 700;
                text-decoration: underline;
                margin: 18px 0px;

                display: flex;
                justify-content: center;
                align-items: center;
            }

            .n-linha {
                width: 100%;
                border: 1pt solid black;
                margin-top: -1px;

                display: flex;
                flex-direction: row;
                align-items: center;
            }

            .n-linha-h1 {
                height: 30px;
            }

            .n-linha-bl {
                border: 1pt solid black;
            }

            .n-col {
                display: flex;
                align-items: center;
            }

            .n-col-1 {
                width: 140px;
                height: 100%;
                border-right: 1pt solid black;
            }

            .n-col-1-lg {
                width: 280px;
                height: 100%;
                border-right: 1pt solid black;
            }


            .n-col-2 {
                width: 220px;
                height: 100%;
                border-right: 1pt solid black;
            }

            .n-col-2-lg {
                width: 320px;
                height: 100%;
                border-right: 1pt solid black;
            }

            .n-col-3{
                width: 240px;
                height: 100%;
                border-right: 1pt solid black;
            }


            .n-texto {
                font-size: 14px;
                padding: 4px;
            }

            p {
                padding: 2px 4px;
            }

            p.lista-files-faltantes {
                margin: 10px 10px;
            }

            .n-footer {
                margin-top: 30px;
            }
        </style>
    </head>

    <body>
        <main>
            <div class="cabecalho-container">
                <div class="logo">
                    <img src="${pathLogoImg}" alt="Logo" width="80%">
                </div>
                <div class="cabecalho">
                    <p class="n-prefeitura">Prefeitura Municipal de Aracruz</p>
                    <p class="n-secretaria">Secretaria Municipal de Fazenda</p>
                    <p class="n-setor">Gerência de Fiscalização e Administração Tributária (GFAT)</p>
                </div>
                <div class="info">
                    <div class='parag-texto'>
                        <p class="n-destaque">NOTIFICAÇÃO</p>
                        <p>PARA APRESENTAÇÃO DE DOCUMENTOS</p>
                    </div>
                    <div class='data-notifica'>
                        <p>DATA: ${dataFormatada}</p>
                    </div>
                </div>
            </div>

            <div class="n-linha-destaque">
                <p>CONTRIBUINTE OU RESPONSÁVEL</p>
            </div>

            <div>

                <div class="n-linha n-linha-h1">
                    <div class="n-col n-col-1">
                        <p>RAZÃO SOCIAL: </p>
                    </div>
                    <div>
                        <p>${empNot.razao_social} </p>
                    </div>
                </div>

                <div class="n-linha n-linha-h1">
                    <div class="n-col n-col-1">
                        <p>CNPJ/MF:</p>
                    </div>
                    <div class="n-col n-col-2">
                        <p>${empNot.cnpj}</p>
                    </div>
                    <div class="n-col n-col-3">
                        <p>INSCRIÇÃO ESTADUAL:</p>
                    </div>
                    <div class="n-col">
                        <p>${empNot.inscricao_estadual}</p>
                    </div>
                </div>

                <div class="n-linha n-linha-h1">
                    <div class="n-col  n-col-1">
                        <p>ENDEREÇO:</p>
                    </div>

                    <div>
                        <p>${empNot.logradouro}, ${empNot.numero}</p>
                    </div>
                </div>
                <div class="n-linha n-linha-h1">
                    <div class="n-col n-col-1">
                        <p>CIDADE (UF):</p>
                    </div>
                    <div class="n-col n-col-2">
                        <p>ARACRUZ (ES)</p>
                    </div>
                    <div class="n-col n-col-1">
                        <p>CEP: </p>
                    </div>
                    <div class="n-col n-col-2">
                        <p>${empNot.cep}</p>
                    </div>
                </div>
                <div class="n-linha n-linha-h1">
                    <div class="n-col n-col-1">
                        <p>COMPLEMENTO:</p>
                    </div>
                    <div>
                        <p>NOTIFICAÇÃO PARA APRESENTAÇÃO DE DOCUMENTOS</p>
                    </div>
                </div>
                <div class="n-linha n-linha-hlg n-texto">
                    <div>
                        <p>
                            Pela presente, o contribuinte acima qualificado fica <strong>notificado a enviar, por meio do
                                link http://fiscalonline.pma.es.gov.br/sped/,<u>no prazo de 10 (dez) dias</u>, o <u>SPED
                                    FISCAL, CONTRIBUIÇÕES e ECD e a DOT com tabela de CFO,</u> conforme listado abaixo, para
                                fins de
                                monitoramento do Valor Adicional Fiscal - VAF, nos termos da Lei Municipal nº
                                4.437/2021:</strong>
                        </p>
                        <p class="lista-files-faltantes">
                            <span>${empNot.filesFaltantesElHtml}</span>
                        </p>

                        <p>
                            Assevera-se que o não cumprimento da obrigação exigida, bem como a omissão de informações à
                            autoridade fazendária constitui crime contra a ordem tributária, conforme previsto no art. 1º da
                            lei
                            federal nº 8.137/1990.
                        </p>
                        <p>
                            <br><strong>Fiscais de Rendas: </strong><br>
                            28933 - ADRIANO JOSE GERMANO DE OLIVEIRA, 26489 - ANDRE CESQUIM TOURINO,
                            2604 - CHIRLE CHAGAS BOFF, 22251 - CLEVERSON MATTIUZZI FARAGE, 847 - LINCON CESAR LIUTH,
                            36958 - RAFAEL COLODETTI SANTOS, 28931 - RAPHAEL MOURÃO GABRIEL, 28932 - SYMONTHON GOMES
                            SANTANA.

                        </p>
                    </div>
                </div>
                <div class="n-linha n-linha-hlg n-texto">
                    <div>
                        <p><strong>E-mail: </strong>vaf.fiscal@aracruz.es.gov.br</p>
                    </div>
                </div>
            </div>





            <div class="n-footer">
                <div class="n-linha n-linha-h1">
                   <p class="quadro-em-branco"></p>
                </div>
                <div class="n-linha n-linha-h1">
                    <div class="n-linha-destaque">
                        <p>CONTRIBUINTE OU RESPONSÁVEL</p>
                    </div>
                </div>
                <div class="n-linha n-linha-h1" style="height: 40px;">
                    <div class="n-col n-col-1">
                        <p>NOME LEGÍVEL:</p>
                    </div>
                    <div></div>
                </div>

                <div class="n-linha n-linha-h1" style="height: 60px; display: flex; align-items: flex-start;">
                    <div class="n-col n-col-1-lg" style="height: 60px; display: flex; align-items: flex-start;">
                        <p>ASSINATURA:</p>
                    </div>

                    <div class="n-col n-col-2" style="height: 60px; display: flex; align-items: flex-start;">
                        <p>DATA DE CIÊNCIA:</p>
                    </div>

                    <div class="n-col">
                        <p>HORA:</p>
                    </div>

                </div>
            </div>
        </main>
    </body>

    </html>`;

    // CRIA UM OBJETO WINDOW
    let win = window.open('', '', 'height=900,width=1200');
    win.document.write(htmlRecibo);
    win.document.close();
    win.print();
}
