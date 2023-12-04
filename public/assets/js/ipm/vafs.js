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
                                return tooltipItem.yLabel.toLocaleString('pt-br', {
                                    style: 'currency',
                                    currency: 'BRL'
                                });;
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

const vafs = {
    data: [],
    filtered: {
        data: []
    },
    cached: {
        anos: []
    },
    els: {
        table: {
            tableCompEmp: isup.$('#tableCompEmp'),
            createTableComp() {
                //--
                const elContainer = vafs.els.table.tableCompEmp;

                const elContent = document.createElement('div')
                elContent.classList.add('dataTables_wrapper')
                elContent.classList.add('dt-bootstrap4')
                elContent.id = 'tableComp_wrapper'
                const elTable = document.createElement('table');
            }
        },

        selected: {
            vafs: isup.$('#selectVafs'),
            empresa: isup.$('#selectEmpresa'),
            anos: isup.$('#selectAnos'),
            createSlectVafs: () => {
                // Elemento para seleção dos VAFs
                const vars = [1, 2, 3, 31, 4]
                vars.forEach(varsItem => {
                    const option = document.createElement('option');
                    option.value = varsItem;
                    option.innerText = `VAF ${varsItem === 31 ? '3 SICOP' : varsItem}`;

                    vafs.els.selected.vafs.appendChild(option)
                })

                vafs.els.selected.vafs.addEventListener('change', () => {
                    vafs.els.selected.createSlectRazao(vafs.els.selected.vafs.value)
                })

            },

            createSlectRazao: (vafIn) => {
                //prepara o elemento
                vafs.els.selected.empresa.innerHTML = `<option disabled selected> - Empresa - </option>`

                const dataFilter = vafs.data.filter(dataItem => dataItem.vaf === +vafIn);

                //Cria Elemento para seleção das Empresa
                const inscricaoUnica = [];
                const dadosfiltrados = [];

                dataFilter.forEach(dataItem => {
                    if (!inscricaoUnica.includes(dataItem.inscricao)) {
                        inscricaoUnica.push(dataItem.inscricao)

                        const option = document.createElement('option');
                        option.value = dataItem.inscricao;
                        option.innerText = `${dataItem.razao_social} - ${dataItem.inscricao}`;
                        vafs.els.selected.empresa.appendChild(option)
                        dadosfiltrados.push(dataItem)
                    }
                })

                vafs.els.selected.empresa.addEventListener('change', () => {
                    vafs.els.selected.createSlectAno(vafIn, vafs.els.selected.empresa.value)
                })

                vafs.filtered.data = dadosfiltrados;
            },

            createSlectAno(vafIn, inscricaoIn) {
                vafs.els.selected.anos.innerHTML = '';

                const anoUnidos = [];
                const anosFiltrados = vafs.data.filter(dataItem =>
                    dataItem.vaf === +vafIn &&
                    dataItem.inscricao === inscricaoIn
                );

                anosFiltrados.forEach(dataItem => {
                    if (!anoUnidos.includes(dataItem.ano)) {
                        anoUnidos.push(dataItem.ano)
                        const option = document.createElement('option');
                        option.value = dataItem.ano;
                        option.innerText = dataItem.ano;
                        vafs.els.selected.anos.appendChild(option)
                    }
                });

                /*vafs.els.selected.anos.addEventListener('change', () => {
                    const novoAno = vafs.els.selected.anos.value
                    if(!vafs.cached.anos.includes(+novoAno) ) {
                        vafs.cached.anos.push(+novoAno)

                    }
                })*/
            }
        },

        buttons: {},

        modalInfor(insc) {
            const model = document.querySelector("#infor-empresa");
            const [dadosModel] = vafs.data.filter(item => item.inscricao_estadual === insc)

            // Melhor usanbdo loop com as keys do objeto =>


            console.log(dadosModel)
            const hiddenFillable = ['vaf', 'valor', 'abertura', 'inscricao', 'rcxsaidas', 'composicao', 'definitivo'];

            Object.keys(dadosModel).forEach((key) => {

                if(hiddenFillable.includes(key)) return false;

                const elDev = document.querySelector(`#info_${key} .info-data`);

                console.log(`#info_${key} .info-data`);
                console.log(elDev);
                console.log(key);

                elDev.innerText = dadosModel[key] === '0' || dadosModel[key] === 0 || dadosModel[key] === null ? 'Não Informado' : dadosModel[key];
            });

            console.log(dadosModel);
        }
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
