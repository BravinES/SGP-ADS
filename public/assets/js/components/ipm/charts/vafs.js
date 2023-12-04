const chart = {

    config: {
        datasetsLabels: ['ano'],
        invert: false
    },

    data: [],
    filter: [],
    home: {
        chart: null,

        create() {
            const elChart = isup.$("#chart-component");

            const dataFilter = chart.data.filter(itemData => {
                let filtered = true;

                Object.keys(chart.filter).forEach((itemFilter) => {
                    if (!(chart.filter[itemFilter].includes(itemData[itemFilter]))) {
                        filtered = false
                    }
                })

                return filtered
            });

            console.log(dataFilter);

            const dataChart = {
                datasets: dataFilter.map((item, index) => ({
                    label: item.razao_social,
                    backgroundColor: isup.colors[index],
                    data: [item.valor]
                }))
            }

            console.log(dataChart);

            //Cria e retora o elemento grafico
            chart.home.chart = new Chart(elChart, {
                type: 'bar',
                data: dataChart,
                options: {
                    responsive: true,
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) {
                                lastHoveredIndex = tooltipItem;
                                return tooltipItem.yLabel.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });;
                            }
                        }
                    },
                }
            })
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
                if (chart.home.filter.ano.includes(item.ano)) {
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
        },

    }
}

//Busca em um lista os anos unicos
function uniqueYearData(data) {
    const uniqueYear = [];
    data.forEach(itemData => {
        if (!uniqueYear.includes(itemData.ano)) {
            uniqueYear.push(itemData.ano);
        }
    })
    uniqueYear.sort()

    return uniqueYear;
}

// Cria os elemento opção do selecte
function optionsYearSelect(elSelects, data) {
    const dateActual = new Date();

    console.log(elSelect, data)

    elSelects.forEach(select => {
        uniqueYearData(data).forEach(option => {
            const createOptions = document.createElement('option');
            createOptions.value = option;
            createOptions.innerHTML = option;
            if (option === dateActual.getFullYear - 1) createOptions.selected = true;
            select.appendChild(createOptions)
        })
    })
}

// Cria os elemento opção do selecte
function optionsYearSelect(elSelects, data, selecionarAnos) {
    const dateActual = new Date();

    console.log(elSelect, data)

    elSelects.forEach(select => {
        uniqueYearData(data).forEach((option, index) => {
            const createOptions = document.createElement('option');
            createOptions.value = option;
            createOptions.innerHTML = option;
            const selected = selecionarAnos ? selecionarAnos[index] : dateActual.getFullYear - 1
            console.log(option, selected, option === selected)

            if (option === selected) createOptions.selected = true;
            select.appendChild(createOptions)
        })
    })
}

function handleYearSelect() {

}
