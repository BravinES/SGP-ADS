const render = {
    async topCard({ elId, url }) {
        const elAreaTopCards = document.getElementById(elId);

        const elTopCard = ({ desc, total, icon }) => /*html*/`<div class="col-lg-3 col-6">
            <div class="small-box bg-dark card-top-receitas">
                <div class="icon">
                    <i class="${icon}"></i>
                </div>
                <div class="inner">
                    <h4>${desc}</h4>
                    <h3>${total}</h3>
                </div>
            </div>
        </div>`

        const cadastroDados = await sun.ajaxWithCache('cadastroDados', url);

        cadastroDados.forEach(item => {
            item.total = (+item.total).toLocaleString('pt-BR');
            elAreaTopCards.innerHTML += elTopCard(item);
        });
    },

    async graficosCadastro({ elId, url }) {
        const areaGraficos = document.getElementById(elId);

        const newElGrafico = ({ title }) => {
            const cardGrafico = document.createElement('div');
            cardGrafico.classList.add('col-lg-6');
            cardGrafico.innerHTML = /*html*/`<div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">${title}</h3>
                </div>

                <div class="card-body sun-chart">
                </div>
            </div>`

            const elGrafico = document.createElement('canvas');
            elGrafico.style = "max-height: 400px; max-width: 400px;";
            cardGrafico.querySelector('.card-body').appendChild(elGrafico);

            return { cardGrafico, elGrafico }
        }

        const coresGrafico = ['#343a40', '#d2d6de', '#f39c12', '#00c0ef', '#3c8dbc'];

        const cadastroDados = await sun.ajaxWithCache('cadastroDados', url);
        const elGraficoCadastro01 = newElGrafico({ title: 'Cadastros Mobiliários e Imobiliários' });
        const elGraficoCadastro02 = newElGrafico({ title: 'Cadastros Pessoa Física e Jurídica' });

        console.log(cadastroDados);

        const graficoCadastro01 = new Chart(elGraficoCadastro01.elGrafico, {
            type: 'pie',
            data: {
                labels: [cadastroDados[0].desc, cadastroDados[1].desc],
                datasets: [{
                    data: [cadastroDados[0].total, cadastroDados[1].total],
                    backgroundColor: [coresGrafico[0], coresGrafico[1]],
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        align: 'bottom',
                        borderRadius: 3,
                        font: {
                            size: 18,
                        }
                    },
                }
            }
        });

        const graficoCadastro02 = new Chart(elGraficoCadastro02.elGrafico, {
            type: 'pie',
            data: {
                labels: [cadastroDados[2].desc, cadastroDados[3].desc],
                datasets: [{
                    data: [cadastroDados[2].total, cadastroDados[3].total],
                    backgroundColor: [coresGrafico[0], coresGrafico[1]],
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        display: true,
                        align: 'bottom',
                        borderRadius: 3,
                        font: {
                            size: 18,
                        }
                    },
                }
            }
        });

        areaGraficos.appendChild(elGraficoCadastro01.cardGrafico);
        areaGraficos.appendChild(elGraficoCadastro02.cardGrafico);
    }
}
