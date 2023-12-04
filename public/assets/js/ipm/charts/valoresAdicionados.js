const chart = {
    home: {
        chart: null,

        config: {
            dataForChart: [],
            datasetsLabels: [],
            invert: false
        },

        filter: {
            municipio: "ARACRUZ",
            ano: []
        },

        create({ dataForChart, datasetsLabels }) {
            const elChart = isup.$("#chart-component");

            // Filtra os dados, inicialmente somente Aracruz
            const dataFilter = dataForChart.filter(item => item.municipio === "ARACRUZ");

            const dataChart = {
                labels: [],
                datasets: datasetsLabels.map((item, index) => ({
                    label: item,
                    backgroundColor: isup.colors[index],
                    data: []
                })),
            }


            //Prepara os dados para o grafico
            dataFilter.forEach(item => {
                dataChart.labels.push(item.ano);
                dataChart.datasets.forEach((labelItem, labelIdenx) => {
                    dataChart.datasets[labelIdenx].data.push(item[labelItem.label] / 100)
                })
            })


            //Cria e retora o elemento grafico
            chart.home.chart = new Chart(elChart, {
                type: 'bar',
                data: dataChart,
                options: {
                    title: {
                        display: true,
                        text: "ARACRUZ"
                    },
                }
            })

            // Item na sidebar de filtros
            const elFilterAno = isup.$('#list-selected-year');

            dataChart.labels.forEach(item => {
                const itemFilter = document.createElement('li');
                itemFilter.classList.add('nav-item');
                itemFilter.classList.add('bg-primary');

                const linkFilter = document.createElement('a');
                linkFilter.href = 'ano/adiciona';
                linkFilter.classList.add('dropdown-item');
                linkFilter.classList.add('text-muted');

                linkFilter.innerText = item

                linkFilter.addEventListener('click', (event) => {
                    event.preventDefault();
                    if (chart.home.filter.ano.includes(+item)) {
                        chart.home.filter.ano.splice(chart.home.filter.ano.indexOf(+item), 1)
                        linkFilter.href = `ano/adiciona`;
                        itemFilter.classList.remove('bg-primary');
                    } else {
                        chart.home.filter.ano.push(+item)
                        chart.home.filter.ano.sort()
                        linkFilter.href = `ano/renove`;
                        itemFilter.classList.add('bg-primary');
                    }

                    chart.home.update();
                })

                itemFilter.appendChild(linkFilter);
                elFilterAno.appendChild(itemFilter);
            });

            // Guarda dados
            chart.home.config.dataForChart = dataForChart;
            chart.home.config.datasetsLabels = datasetsLabels;
            chart.home.filter.ano = dataChart.labels;

        },

        update() {
            const dataFilter = chart.home.config.dataForChart.filter(item => item.municipio === chart.home.filter.municipio);

            const dataChart = {
                labels: [],
                datasets: chart.home.config.datasetsLabels.map((item, index) => ({
                    label: item,
                    backgroundColor: isup.colors[index],
                    data: []
                })),
            }

            //Prepara os dados para o grafico
            dataFilter.forEach(item => {
                if(chart.home.filter.ano.includes(item.ano)){
                    dataChart.labels.push(item.ano);
                    dataChart.datasets.forEach((labelItem, labelIdenx) => {
                        dataChart.datasets[labelIdenx].data.push(item[labelItem.label] / 100)
                    })
                }
            })

            chart.home.chart.data.datasets = dataChart.datasets;
            chart.home.chart.options.title.text = chart.home.filter.municipio;
            chart.home.chart.data.labels = dataChart.labels;
            chart.home.chart.update();
        },

        updateInvert() {

            const dataFilter = chart.home.config.dataForChart
                .filter(item => item.municipio === chart.home.filter.municipio &&
                                chart.home.filter.ano.includes(item.ano)
            );



            const dataChart = {
                labels: chart.home.config.datasetsLabels,
                datasets: dataFilter.map((item, index) => ({
                    label: item.ano,
                    backgroundColor: isup.colors[index],
                    data: []
                })),
            }

            console.log(dataFilter, dataChart)


            //Prepara os dados para o grafico
           dataFilter.forEach((itemFilter, index) => {
               dataChart.datasets[index].data = [
                   itemFilter.vaf_1 / 100,
                   itemFilter.vaf_2 / 100,
                   itemFilter.vaf_3 / 100,
                   itemFilter.vaf_4 / 100,
                   itemFilter.total / 100,
                   itemFilter.composicao / 100
               ]
            })

            chart.home.chart.data.datasets = dataChart.datasets;
            chart.home.chart.options.title.text = chart.home.filter.municipio;
            chart.home.chart.data.labels = dataChart.labels;
            chart.home.chart.update();
        },

        handleUpdateInvert() {
            if (!chart.home.config.invert) {
                chart.home.updateInvert();
                chart.home.config.invert = true;
            } else {
                chart.home.update();
                chart.home.config.invert = false;
            }
        }
    }
}
