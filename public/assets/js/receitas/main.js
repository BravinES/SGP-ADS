const render = {
    dataReceitas: {
        graficos: {},
        filtros: { ano: [2016, 2017, 2018, 2019, 2020, 2021, 2022], grupo: [] },
        anos: [],
    },
    localCache: {},
    async topCard({ elId, url, anoDados, pause }) {

        const elAreaTopCards = document.getElementById(elId);
        elAreaTopCards.innerHTML = '';
        elAreaTopCards.classList.add('loading');

        const anoCards = anoDados ? +anoDados : new Date().getFullYear();
        pause && await _.pause(pause);

        const elTopCard = ({ titulo, cardDados, icon, classCard }) => {
            const cardInfo = cardDados.map(card => /*html*/`<div class="card-infor ${card.classInfo ? card.classInfo : ''}">
                <span class="description">${card.desc}</span>
                <span class="dados">${card.valor}</span>
            </div>\n`).join('');

            return /*html*/`<div class="col-xl-3 col-lg-4 col-md-6 col-12">
                <div class="small-box card-top ${classCard && classCard.bgColor ? classCard.bgColor : 'bg-dark'}">
                    <div class="icon">
                        <i class="${icon}"></i>
                    </div>
                    <div class="inner">
                        <h4 class="card-title">${titulo}</h4>
                        ${cardInfo}
                    </div>
                </div>
            </div>`
        }

        const { receitasBalanco, totais } = await _fnc().dadosRedeitas(url);
        elAreaTopCards.classList.remove('loading');

        const cardIptu = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'IPTU' && item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'IPTU',
                cardDados: [
                    { desc: 'Lancado:', valor: `R$ ${parseFloat(filterData.total_lancado).toLocaleString('pt-BR')}` },
                    { desc: 'Arrecadado:', valor: `R$ ${parseFloat(filterData.total_arrecadado).toLocaleString('pt-BR')}` }
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardIssFixo = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'ISS FIXO' && item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'ISS Fixo',
                cardDados: [
                    { desc: 'Lancado:', valor: `R$ ${parseFloat(filterData.total_lancado).toLocaleString('pt-BR')}` },
                    { desc: 'Arrecadado:', valor: `R$ ${parseFloat(filterData.total_arrecadado).toLocaleString('pt-BR')}` }
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardIssVariavel = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'ISS MENSAL' && item.ano === anoCards);
            const prestador = filterData.filter(item => item.substituicao === 'Prestador')[0];
            const tomador = filterData.filter(item => item.substituicao === 'Tomador')[0];

            return elTopCard({
                titulo: 'ISS - Variável',
                cardDados: [
                    { desc: 'Prestador:', valor: `R$ ${parseFloat(prestador.total_arrecadado).toLocaleString('pt-BR')}` },
                    { desc: 'Tomador:', valor: `R$ ${parseFloat(tomador.total_arrecadado).toLocaleString('pt-BR')}` },
                    { desc: 'TOTAL:', valor: `R$ ${parseFloat(prestador.total_arrecadado + tomador.total_arrecadado).toLocaleString('pt-BR')}`, classInfo: 'card-total' }
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardItbi = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'ITBI' && item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'ITBI',
                cardDados: [
                    { desc: 'Arrecadado:', valor: `R$ ${parseFloat(filterData.total_arrecadado).toLocaleString('pt-BR')}` },
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardDividaAtiva = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'DÍVIDA ATIVA' && item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'Dívida Ativa',
                cardDados: [
                    { desc: 'Arrecadado:', valor: `R$ ${parseFloat(filterData.total_arrecadado).toLocaleString('pt-BR')}` },
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardAutoInfracao = () => {
            const filterData = receitasBalanco.filter(item => item.grupo === 'AUTO DE INFRAÇÃO' && item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'Auto de Infração ',
                cardDados: [
                    { desc: 'Arrecadado:', valor: `R$ ${parseFloat(filterData.total_arrecadado).toLocaleString('pt-BR')}` },
                ],
                icon: 'fas fa-money-bill-wave'
            })
        }

        const cardTotalGeral = () => {
            const filterData = totais.filter(item => item.ano === anoCards)[0];

            return elTopCard({
                titulo: 'Total Receitas',
                cardDados: [
                    {
                        desc: 'Total:',
                        valor: `R$ ${parseFloat(filterData.total).toLocaleString('pt-BR')}`,
                    },
                ],
                classCard: { bgColor: 'bg-card-light' },
                icon: 'fas fa-money-bill-wave'
            })
        }

        elAreaTopCards.innerHTML = cardIptu();
        elAreaTopCards.innerHTML += cardIssFixo();
        elAreaTopCards.innerHTML += cardIssVariavel();
        elAreaTopCards.innerHTML += cardItbi();
        elAreaTopCards.innerHTML += cardDividaAtiva();
        elAreaTopCards.innerHTML += cardAutoInfracao();
        elAreaTopCards.innerHTML += cardTotalGeral();

        render.localCache.fncMudarAno.elAnoTotais.classList.add('year-hover');
        render.localCache.fncMudarAno.elAnoTotais.querySelector(`.btn-year`).innerHTML = anoCards;
        /* receitasDados.forEach(item => {
            item.total = (+item.parseFloat(total).)toLocaleString('pt-BR');
            elAreaTopCards.innerHTML += elTopCard(item);
        });*/
    },

    async graficosReceitas({ elId, url }) {
        const areaGraficos = document.getElementById(elId);

        const elGrafico = ({ title, cols }) => {
            const cardGrafico = document.createElement('div');
            cardGrafico.classList.add('col-12');
            cols && cardGrafico.classList.add(cols);
            cardGrafico.innerHTML = /*html*/`<div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">${title}</h3>
                </div>

                <div class="card-body receitas-chart"></div>
            </div>`

            const canvasGrafico = document.createElement('canvas');
            canvasGrafico.style = "max-height: 400px;";
            cardGrafico.querySelector('.card-body').appendChild(canvasGrafico);

            return { cardGrafico, canvasGrafico }
        }

        const GraficoIptu = async () => {
            const elGraficoIptu = elGrafico({ title: 'IPTU' });
            const dadosGraficoIptu = await _fnc().filtroDadosRedeitas('IPTU', url);

            const labelIptu = [];
            const datasetIptu = {
                lancados: {
                    label: 'Lancados',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                }
            };

            dadosGraficoIptu.forEach(item => {
                labelIptu.push(item.ano);
                datasetIptu.lancados.data.push(item.total_lancado);
                datasetIptu.arrecadados.data.push(item.total_arrecadado);
            });

            render.dataReceitas.graficos.iptu = {
                chart: new Chart(elGraficoIptu.canvasGrafico, {
                    type: 'bar',
                    data: {
                        labels: labelIptu,
                        datasets: [datasetIptu.lancados, datasetIptu.arrecadados]
                    },
                }),
                name: 'iptu',
                data: 'IPTU',
            };

            return elGraficoIptu.cardGrafico;
        }

        const GraficoIssFixo = async () => {
            const elGraficoIssFixo = elGrafico({ title: 'ISS Fixo' });
            const dadosGraficoIssFixo = await _fnc().filtroDadosRedeitas('ISS FIXO', url);

            const labelIssFixo = [];
            const datasetIssFixo = {
                lancados: {
                    label: 'Lancados',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                }
            };

            dadosGraficoIssFixo.forEach(item => {
                labelIssFixo.push(item.ano);
                datasetIssFixo.lancados.data.push(item.total_lancado);
                datasetIssFixo.arrecadados.data.push(item.total_arrecadado);
            });

            render.dataReceitas.graficos.issFixo = {
                chart: new Chart(elGraficoIssFixo.canvasGrafico, {
                    type: 'bar',
                    data: {
                        labels: labelIssFixo,
                        datasets: [datasetIssFixo.lancados, datasetIssFixo.arrecadados]
                    },
                }),

                name: 'ISS Fixo',
                data: 'ISS FIXO',
            };

            return elGraficoIssFixo.cardGrafico;
        }

        const GraficoIssVariavel = async () => {
            const elGraficoIssVariavel = elGrafico({ title: 'ISS Variável' });
            const dadosGraficoIssVariavel = await _fnc().filtroDadosRedeitas('ISS MENSAL', url);

            const anoIssVariavel = [];


            dadosGraficoIssVariavel.forEach(item => {
                if (!anoIssVariavel.includes(item.ano)) {
                    anoIssVariavel.push(item.ano);
                }
            });

            const dadosUnicosIssVariavel = anoIssVariavel.map(item => {
                const unicoAnoTomador = dadosGraficoIssVariavel.filter(itemIss => itemIss.ano === item && itemIss.substituicao === 'Tomador');
                const unicoAnoPrestador = dadosGraficoIssVariavel.filter(itemIss => itemIss.ano === item && itemIss.substituicao === 'Prestador');

                return {
                    ano: item,
                    tomador: unicoAnoTomador[0].total_arrecadado,
                    prestador: unicoAnoPrestador[0].total_arrecadado,
                    total: unicoAnoTomador[0].total_arrecadado + unicoAnoPrestador[0].total_arrecadado
                }
            })

            const labelIssVariavel = [];
            const datasetIssVariavel = {
                tomador: {
                    label: 'Tomador',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                prestador: {
                    label: 'Prestador',
                    data: [],
                    backgroundColor: _.colors[1],
                },
                total: {
                    label: 'Total',
                    data: [],
                    backgroundColor: _.colors[2],
                }
            };

            dadosUnicosIssVariavel.forEach(item => {
                labelIssVariavel.push(item.ano);
                datasetIssVariavel.tomador.data.push(item.tomador);
                datasetIssVariavel.prestador.data.push(item.prestador);
                datasetIssVariavel.total.data.push(item.total);
            });

            render.dataReceitas.graficos.issVariavel = {
                chart: new Chart(elGraficoIssVariavel.canvasGrafico, {
                    type: 'bar',
                    data: {
                        labels: labelIssVariavel,
                        datasets: [datasetIssVariavel.tomador, datasetIssVariavel.prestador, datasetIssVariavel.total]
                    },
                }),
                name: 'Iss Variável',
                data: 'ISS MENSAL',
            };

            return elGraficoIssVariavel.cardGrafico;
        }

        const GraficoItbi = async () => {
            const elGraficoItbi = elGrafico({ title: 'ITBI', cols: 'col-md-6' });
            const dadosGraficoItbi = await _fnc().filtroDadosRedeitas('ITBI', url);

            const labelItbi = [];
            const datasetItbi = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoItbi.forEach(item => {
                labelItbi.push(item.ano);
                datasetItbi.arrecadados.data.push(item.total_arrecadado);
            });

            render.dataReceitas.graficos.itbi = {
                chart: new Chart(elGraficoItbi.canvasGrafico, {
                    type: 'line',
                    data: {
                        labels: labelItbi,
                        datasets: [datasetItbi.arrecadados]
                    },
                }),
                name: 'Itbi',
                data: 'ITBI',
            };

            return elGraficoItbi.cardGrafico;
        }

        const GraficoDividaAtiva = async () => {
            const elGraficoDividaAtiva = elGrafico({ title: 'Dívida Ativa', cols: 'col-md-6' });
            const dadosGraficoDividaAtiva = await _fnc().filtroDadosRedeitas('DÍVIDA ATIVA', url);

            const labelDividaAtiva = [];
            const datasetDividaAtiva = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoDividaAtiva.forEach(item => {
                labelDividaAtiva.push(item.ano);
                datasetDividaAtiva.arrecadados.data.push(item.total_arrecadado);
            });

            render.dataReceitas.graficos.DividaAtiva = {
                chart: new Chart(elGraficoDividaAtiva.canvasGrafico, {
                    type: 'line',
                    data: {
                        labels: labelDividaAtiva,
                        datasets: [datasetDividaAtiva.arrecadados]
                    },
                }),
                name: 'Dívida Ativa',
                data: 'DÍVIDA ATIVA',
            };

            return elGraficoDividaAtiva.cardGrafico;
        }

        const GraficoAutoInfracao = async () => {
            const elGraficoAutoInfracao = elGrafico({ title: 'Auto Infração', cols: 'col-md-6' });
            const dadosGraficoAutoInfracao = await _fnc().filtroDadosRedeitas('AUTO DE INFRAÇÃO', url);

            const labelAutoInfracao = [];
            const datasetAutoInfracao = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoAutoInfracao.forEach(item => {
                labelAutoInfracao.push(item.ano);
                datasetAutoInfracao.arrecadados.data.push(item.total_arrecadado);
            });

            render.dataReceitas.graficos.autoInfracao = {
                chart: new Chart(elGraficoAutoInfracao.canvasGrafico, {
                    type: 'line',
                    data: {
                        labels: labelAutoInfracao,
                        datasets: [datasetAutoInfracao.arrecadados]
                    },
                }),
                name: 'Auto Infração',
                data: 'AUTO DE INFRAÇÃO',
            };

            return elGraficoAutoInfracao.cardGrafico;
        }

        const GraficoTotais = async () => {
            const elGraficoTotais = elGrafico({ title: 'Total por Ano', cols: 'col-md-6' });
            const dadosGraficoTotais = await _fnc().filtroDadosRedeitasTotais(url);

            const labelTotais = [];
            const datasetTotais = {
                total: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[7],
                    borderColor: _.colors[7],
                }
            };

            dadosGraficoTotais.forEach(item => {
                labelTotais.push(item.ano);
                datasetTotais.total.data.push(item.total);
            });

            render.dataReceitas.graficos.totais = {
                chart: new Chart(elGraficoTotais.canvasGrafico, {
                    type: 'line',
                    data: {
                        labels: labelTotais,
                        datasets: [datasetTotais.total]
                    },
                }),
                name: 'Total por Ano',
                data: 'TOTAIS',

            };

            return elGraficoTotais.cardGrafico;
        }


        areaGraficos.appendChild(await GraficoIptu());
        areaGraficos.appendChild(await GraficoIssFixo());
        areaGraficos.appendChild(await GraficoIssVariavel());
        areaGraficos.appendChild(await GraficoItbi());
        areaGraficos.appendChild(await GraficoDividaAtiva());
        areaGraficos.appendChild(await GraficoAutoInfracao());
        areaGraficos.appendChild(await GraficoTotais());

        render.localCache.urlFilter = url;

    },

    async mudarAnoTotais({ elId, elCardsID, url }) {

        const elAnoTotais = document.getElementById(elId);

        render.localCache.fncMudarAno = { elAnoTotais, elCardsID, url };

        const elAno = (listaAnos) => {
            const elAnos = listaAnos.map(ano => /*html*/`<li onclick="_fnc().mudarAno(${ano})">${ano}</li>`).join('');
            const listYear = document.createElement('div');
            listYear.classList.add('list-year');
            listYear.innerHTML = /*html*/`<ul>${elAnos}</ul>`
            return listYear;
        };

        render.dataReceitas.anos.length === 0 && await _fnc().dadosRedeitas(url);

        const listYear = render.dataReceitas.anos.map(ano => ano).sort((a, b) => b - a)
        elAnoTotais.appendChild(elAno(listYear));
    },

    async filtroGraficos({ elId, url }) {
        const elFiltro = document.getElementById(elId);

        render.dataReceitas.anos.length === 0 && await _fnc().dadosRedeitas(url);
        const listYear = render.dataReceitas.anos.map(ano => ano).sort((a, b) => b - a)

        const elFiltroGraficos = () => {
            const cardFiltroGraficos = document.createElement('div');
            cardFiltroGraficos.classList.add('card-filtro-graficos');

            const elListYear = listYear.map(anoItem => {
                const check = render.dataReceitas.filtros.ano.includes(anoItem) ? 'checked' : ''

                return/*html*/`<li onchange="_fnc().toggleYearFilter(${anoItem})">
                    <input type="checkbox" id="checkbox${anoItem}" ${check}>
                        <label for="checkbox${anoItem}">${anoItem}</label>
                </li>`
            }).join('');

            cardFiltroGraficos.innerHTML = /*html*/`<div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Anos</h3>
                </div>
                <div class="card-body">
                    <ul class="list-filter">
                        ${elListYear}
                    </ul>
                </div>
            </div>`

            return cardFiltroGraficos
        }

        elFiltro.appendChild(elFiltroGraficos());

    },

}

function _fnc() {
    return {
        async dadosRedeitas(url) {
            if (!_.getLocalStorage('receitasBalanco')) {

                const anosReceita = [];
                const grupoReceita = [];
                const receitasBalancoLancadas = [];
                const receitasBalancoArrecadados = [];

                const dataBD = await sun.getData(url);
                receitasLancadas = [...dataBD.receitas_lancadas];

                receitasLancadas.forEach(item => {
                    if (!grupoReceita.includes(item.grupo)) {
                        grupoReceita.push(item.grupo);
                    }
                    if (!anosReceita.includes(+item.ano) && +item.ano >= 2010) {
                        anosReceita.push(+item.ano);
                    }
                });

                grupoReceita.forEach(grupo => {
                    anosReceita.forEach(ano => {
                        const total_lancado = [...dataBD.receitas_lancadas.filter(item => item.grupo === grupo && +item.ano === ano)][0];
                        const total_arrecadado = [...dataBD.receitas_arrecadadas_ano.filter(item => item.grupo === grupo && +item.ano === ano)][0];

                        if (total_lancado && total_arrecadado) {
                            receitasBalancoLancadas.push({
                                grupo: grupo,
                                ano: +ano,
                                total_lancado: total_lancado ? parseFloat(parseFloat(total_lancado.total_lancado).toFixed(2)) : 0,
                                total_arrecadado: total_arrecadado ? parseFloat(parseFloat(total_arrecadado.total_arrecadado).toFixed(2)) : 0,
                            })
                        }
                    });
                });

                dataBD.receitas_arrecadadas_ano.forEach(item => {
                    if (!grupoReceita.includes(item.grupo)) {
                        receitasBalancoArrecadados.push({
                            grupo: item.grupo,
                            ano: +item.ano,
                            total_arrecadado: parseFloat(parseFloat(item.total_arrecadado).toFixed(2)),
                            substituicao: (item.substituicao) ? item.substituicao : false,
                        })
                    }
                });

                const receitasBalanco = [...receitasBalancoLancadas, ...receitasBalancoArrecadados];

                const totalGeral = dataBD.receitas_arrecadadas_totais.map(item => {
                    return {
                        ano: +item.ano,
                        total: parseFloat(parseFloat(item.total).toFixed(2)),
                    }
                });

                _.setLocalStorage('receitasBalanco', receitasBalanco, 720);
                _.setLocalStorage('anosReceita', anosReceita, 720);
                _.setLocalStorage('grupoReceita', grupoReceita, 720);
                _.setLocalStorage('receitas_arrecadadas_totais', totalGeral, 720);
            }

            render.dataReceitas.anos = _.getLocalStorage('anosReceita');

            return {
                receitasBalanco: _.getLocalStorage('receitasBalanco'),
                anosReceita: _.getLocalStorage('anosReceita'),
                grupoReceita: _.getLocalStorage('grupoReceita'),
                totais: _.getLocalStorage('receitas_arrecadadas_totais')
            }

        },

        async filtroDadosRedeitas(grupo, url) {
            let { receitasBalanco: filterData } = await _fnc().dadosRedeitas(url);

            if (render.dataReceitas.filtros.ano.length > 0) {
                filterData = filterData.filter(item => render.dataReceitas.filtros.ano.includes(item.ano));
            }

            if (render.dataReceitas.filtros.grupo.length > 0 || 1) {
                filterData = filterData.filter(item => item.grupo === grupo);
            }

            return filterData.sort((a, b) => parseInt(a.ano) - parseInt(b.ano));
        },

        async filtroDadosRedeitasTotais(url) {
            let { totais } = await _fnc().dadosRedeitas(url);

            if (render.dataReceitas.filtros.ano.length > 0) {
                filterData = totais.filter(item => render.dataReceitas.filtros.ano.includes(item.ano));
            }

            return filterData.sort((a, b) => parseInt(a.ano) - parseInt(b.ano));
        },

        async updateChart() {
            /*ITPU Update*/

            const dadosGraficoIptu = await _fnc().filtroDadosRedeitas('IPTU', render.localCache.urlFilter);
            const labelIptu = [];
            const datasetIptu = {
                lancados: {
                    label: 'Lancados',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                }
            };

            dadosGraficoIptu.forEach(item => {
                labelIptu.push(item.ano);
                datasetIptu.lancados.data.push(item.total_lancado);
                datasetIptu.arrecadados.data.push(item.total_arrecadado);
            });

            const chartIptu = render.dataReceitas.graficos.iptu.chart;

            chartIptu.data.labels = labelIptu;
            chartIptu.data.datasets = [datasetIptu.lancados, datasetIptu.arrecadados]
            chartIptu.update();


            /*ISS Fixo Update*/
            const dadosGraficoIssFixo = await _fnc().filtroDadosRedeitas('ISS FIXO', render.localCache.urlFilter);
            const labelIssFixo = [];
            const datasetIssFixo = {
                lancados: {
                    label: 'Lancados',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                }
            };

            dadosGraficoIssFixo.forEach(item => {
                labelIssFixo.push(item.ano);
                datasetIssFixo.lancados.data.push(item.total_lancado);
                datasetIssFixo.arrecadados.data.push(item.total_arrecadado);
            });

            const chartIss = render.dataReceitas.graficos.issFixo.chart;

            chartIss.data.labels = labelIssFixo;
            chartIss.data.datasets = [datasetIssFixo.lancados, datasetIssFixo.arrecadados]
            chartIss.update();





            /* Update  the chart IssVariavel*/
            const dadosGraficoIssVariavel = await _fnc().filtroDadosRedeitas('ISS MENSAL', render.localCache.urlFilter);
            const anoIssVariavel = [];


            dadosGraficoIssVariavel.forEach(item => {
                if (!anoIssVariavel.includes(item.ano)) {
                    anoIssVariavel.push(item.ano);
                }
            });


            const dadosUnicosIssVariavel = anoIssVariavel.map(item => {
                const unicoAnoTomador = dadosGraficoIssVariavel.filter(itemIss => itemIss.ano === item && itemIss.substituicao === 'Tomador');
                const unicoAnoPrestador = dadosGraficoIssVariavel.filter(itemIss => itemIss.ano === item && itemIss.substituicao === 'Prestador');

                return {
                    ano: item,
                    tomador: unicoAnoTomador[0].total_arrecadado,
                    prestador: unicoAnoPrestador[0].total_arrecadado,
                    total: unicoAnoTomador[0].total_arrecadado + unicoAnoPrestador[0].total_arrecadado
                }
            })

            const labelIssVariavel = [];
            const datasetIssVariavel = {
                tomador: {
                    label: 'Tomador',
                    data: [],
                    backgroundColor: _.colors[0],
                },
                prestador: {
                    label: 'Prestador',
                    data: [],
                    backgroundColor: _.colors[1],
                },
                total: {
                    label: 'Total',
                    data: [],
                    backgroundColor: _.colors[2],
                }
            };

            dadosUnicosIssVariavel.forEach(item => {
                labelIssVariavel.push(item.ano);
                datasetIssVariavel.tomador.data.push(item.tomador);
                datasetIssVariavel.prestador.data.push(item.prestador);
                datasetIssVariavel.total.data.push(item.total);
            });



            const chartIssVariavel = render.dataReceitas.graficos.issVariavel.chart;
            chartIssVariavel.data.labels = labelIssVariavel;
            chartIssVariavel.data.datasets = [datasetIssVariavel.tomador, datasetIssVariavel.prestador, datasetIssVariavel.total]
            chartIssVariavel.update();


            /* Update  the chart Itbi*/
            const dadosGraficoItbi = await _fnc().filtroDadosRedeitas('ITBI', render.localCache.urlFilter);
            const labelItbi = [];
            const datasetItbi = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoItbi.forEach(item => {
                labelItbi.push(item.ano);
                datasetItbi.arrecadados.data.push(item.total_arrecadado);
            });

            const chartItbi = render.dataReceitas.graficos.itbi.chart;

            chartItbi.data.labels = labelItbi;
            chartItbi.data.datasets = [datasetItbi.arrecadados]
            chartItbi.update();

            /* Update  the chart  DividaAtiva*/
            const dadosGraficoDividaAtiva = await _fnc().filtroDadosRedeitas('DÍVIDA ATIVA', render.localCache.urlFilter);
            const labelDividaAtiva = [];
            const datasetDividaAtiva = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoDividaAtiva.forEach(item => {
                labelDividaAtiva.push(item.ano);
                datasetDividaAtiva.arrecadados.data.push(item.total_arrecadado);
            });

            const chartDividaAtiva = render.dataReceitas.graficos.DividaAtiva.chart;

            chartDividaAtiva.data.labels = labelDividaAtiva;
            chartDividaAtiva.data.datasets = [datasetDividaAtiva.arrecadados]
            chartDividaAtiva.update();



            /* Update  the chart AutoInfracao*/
            const dadosGraficoAutoInfracao = await _fnc().filtroDadosRedeitas('AUTO DE INFRAÇÃO', render.localCache.urlFilter);
            const labelAutoInfracao = [];
            const datasetAutoInfracao = {
                arrecadados: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[1],
                    borderColor: _.colors[1],
                }
            };

            dadosGraficoAutoInfracao.forEach(item => {
                labelAutoInfracao.push(item.ano);
                datasetAutoInfracao.arrecadados.data.push(item.total_arrecadado);
            });

            const chartAutoInfracao = render.dataReceitas.graficos.autoInfracao.chart;

            chartAutoInfracao.data.labels = labelAutoInfracao;
            chartAutoInfracao.data.datasets = [datasetAutoInfracao.arrecadados]
            chartAutoInfracao.update();

            /* Update  the chart Totais */
            const dadosGraficoTotais = await _fnc().filtroDadosRedeitasTotais(render.localCache.urlFilter);

            const labelTotais = [];
            const datasetTotais = {
                total: {
                    label: 'Arrecadados',
                    data: [],
                    backgroundColor: _.colors[7],
                    borderColor: _.colors[7],
                }
            };

            dadosGraficoTotais.forEach(item => {
                labelTotais.push(item.ano);
                datasetTotais.total.data.push(item.total);
            });


            const chartTotais = render.dataReceitas.graficos.totais.chart;

            chartTotais.data.labels = labelTotais;
            chartTotais.data.datasets = [datasetTotais.total]
            chartTotais.update();

















        },

        mudarAno(ano) {
            render.topCard({
                elId: render.localCache.fncMudarAno.elCardsID,
                url: render.localCache.fncMudarAno.url,
                anoDados: ano,
                pause: 1
            });

            render.localCache.fncMudarAno.elAnoTotais.classList.remove('year-hover');
        },

        toggleYearFilter(year) {
            if (render.dataReceitas.filtros.ano.includes(year)) {
                render.dataReceitas.filtros.ano = render.dataReceitas.filtros.ano.filter(item => item !== year);
            } else {
                render.dataReceitas.filtros.ano.push(year);
            }
            render.dataReceitas.filtros.ano.sort((a, b) => parseInt(a) - parseInt(b));

            _fnc().updateChart();
        }

    }
}
