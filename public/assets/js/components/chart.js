const chartPanel = {
    create(config) {

        // Elemento que vai receber o gráfico
        const el = document.querySelector(`${config.el} canvas#chart`);
        if (!el) return false;

        // Filtrar os dados
        const dataFilter = config.filters ? config.dataForChart.filter((data, index) => {
            let filtered = true;
            config.filters.forEach(filter => {

                if (filter[1] === "between") {
                    if (!(data[filter[0]] >= filter[2][0] && data[filter[0]] <= filter[2][1])) filtered = false
                } else {
                    if (!eval(`${data[filter[0]]} ${filter[1] + " " + filter[2]}`)) filtered = false
                }
            })

            return filtered;
        }) : config.dataForChart;

        // Preparar os dados para o gráfico
        let labels = [];

        const column = config.columns.map(columnItem => []);

        dataFilter.forEach((dataItem, dataIndex) => {
            labels.push(dataItem[config.legend])

            config.columns.forEach((columnItem, columnIndex) => {
                column[columnIndex].push(dataItem[columnItem.field])
            })
        })

        const datasets = config.columns.map((columnItem, columnIndex) => {
            return {
                label: columnItem.name,

                backgroundColor: chartColors[config.columns.length - columnIndex - 1],
                fill: config.type !== "line" ? true : false,
                borderColor: chartColors[config.columns.length - columnIndex - 1],

                data: column[columnIndex]
            }
        });


        return new Chart(el.getContext("2d"), {
            type: config.type || 'bar',
            data: {
                labels,
                datasets
            },

            options: {
                responsive: true,
                plugins: {
                },
                tooltips: {
                    callbacks: {
                        title: function () {
                            return '';
                        },
                        label: function (tooltipItem, data) {
                            lastHoveredIndex = tooltipItem;
                            return tooltipItem.yLabel.toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });;
                        }
                    }
                },
                options: {
                    plugins: {
                        legend: {
                            onClick: () => alert('oi')
                        }
                    },

                },
                onClick: function (e, items) {
                    if (!config.url || !items[0]) return
                    const label = items[0]._model.label
                    window.location.href = `${config.url}?year=${label}`;
                }
            }
        })
    },
    update(chart, config) {

        // Filtrar os dados
        const dataFilter = config.filters ? config.dataForChart.filter((data, index) => {
            let filtered = true;
            config.filters.forEach(filter => {
                if (filter[1] === "between") {
                    if (!(data[filter[0]] >= filter[2][0] && data[filter[0]] <= filter[2][1])) filtered = false
                } else {
                    if (!eval(`${data[filter[0]]} ${filter[1] + " " + filter[2]}`)) filtered = false
                }
            })

            return filtered;
        }) : config.dataForChart;

        // Preparar os dados para o gráfico
        let labels = [];

        const column = config.columns.map(columnItem => []);

        dataFilter.forEach((dataItem, dataIndex) => {
            labels.push(dataItem[config.legend])

            config.columns.forEach((columnItem, columnIndex) => {
                column[columnIndex].push(dataItem[columnItem.field])
            })
        })

        chart.data.labels = labels;

        const dataset = column.map((columnItem, index) => ({
            label: config.columns[index].name,
            data: columnItem,
            backgroundColor: chartColors[column.length - index - 1],
            fill: config.type !== "line" ? true : false,
            borderColor: chartColors[column.length - index - 1],
        }));

        chart.data.datasets = dataset

        chart.update();

    },
    updateType(chart, type) {
        chart.data.datasets.forEach((dataset, index) => {
            dataset.backgroundColor = type !== "line" ? chartColors[index] : "transparent";
            dataset.borderColor = chartColors[index];
        });
        chart.config.type = type;
        chart.update();
    }
}

const chartIptu = {
    year: {
        create(data) {
            data.filter.el.forEach((el, index) => {
                const falterEls = document.querySelectorAll(el);
                const options = data.chart.dataForChart.map(dataItem => dataItem[data.filter.value[index]])
                falterEls.forEach(falterEl => {
                    options.forEach(option => {
                        const createOptions = document.createElement('option');
                        createOptions.value = option;
                        createOptions.innerHTML = option;
                        if (option === 2021) createOptions.selected = true;
                        falterEl.appendChild(createOptions)
                    })

                    falterEl.addEventListener('change', data.filter.fnc[0])
                })
            })

            return chartPanel.create(data.chart)
        },
        update(chart, data) {

            const yearStart = document.querySelector('#yearStart').value;
            const yearEnd = document.querySelector('#yearEnd').value

            let yearChart = yearStart <= yearEnd ?
                [yearStart, yearEnd] : [yearEnd, yearStart]

            data.filters[0] = [
                'year', 'between', yearChart
            ];
            chartPanel.update(chart, data)
        }
    },

    month: {
        yearSelected: [],
        filter: {
            type: ['001', '002', '003', '004', '005'],
            occupation: ['001', '002', '003', '004', '005']
        },
        create(data, yearFilter) {

            const yearArray = Array.isArray(yearFilter) ? yearFilter.map(item => +item) : [+yearFilter];
            chartIptu.month.yearSelected = yearArray

            const filterData = data.chart.dataForChart.filter(dataItem => {
                const dataItemDate = new Date(dataItem.date);
                return yearArray.includes(dataItemDate.getFullYear())
            })

            const filterDataOrder = []
            for (let i = 0; i < 12; i++) {
                let soma = 0;

                filterData.forEach(item => {
                    const itemDate = new Date(item.date);

                    if (itemDate.getMonth() === i) {
                        soma = soma + Number(item.collected_value)
                    }
                })
                filterDataOrder.push({ month: monthNames[i], [`year_value_${yearFilter}`]: soma })
            }


            console.log(filterDataOrder)

            // Anos selecionados
            const yearSelected = document.querySelector('#year-selected');
            yearArray.forEach(year => {
                const newHtml = `
                <div id="yearSelected_${year}" class="col-md-12">
                    <span class="btn btn-outline-secondary col-md-5 mr-2">${year}</span>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm col-md-3"
                        onclick="() => {console.log('remover' + year)}"
                        >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                `;
                yearSelected.innerHTML = newHtml;
            })

            //Prennce os Anos nos selects
            const falterEl = document.querySelector(data.filter.el);
            const options = []

            data.chart.dataForChart.forEach((dataItem, dataIndex) => {
                const dataItemDate = new Date(dataItem.date)
                if (!options.includes(dataItemDate.getFullYear())) {
                    options.push(dataItemDate.getFullYear())
                }
            })

            options.sort()

            options.forEach(option => {
                const createOptions = document.createElement('option');
                createOptions.value = option;
                createOptions.innerHTML = option;
                if (option === 2021) createOptions.selected = true;
                falterEl.appendChild(createOptions)
            })

            return chartPanel.create({
                columns: data.chart.columns,
                el: data.chart.el,
                legend: data.chart.legend,
                dataForChart: filterDataOrder,
                type: "line"
            })


        },

        update(chart, data) {
            const yearArray =
                Array.isArray(chartIptu.month.yearSelected) ?
                    chartIptu.month.yearSelected.map(item => +item) : [+chartIptu.month.yearSelected];
            yearArray.sort()

            const filterData = data.dataForChart.filter(dataItem => {
                const dataItemDate = new Date(dataItem.date);



                if (!yearArray.includes(dataItemDate.getFullYear())) return false;
                if (!chartIptu.month.filter.type.includes(dataItem.type)) return false;
                if (!chartIptu.month.filter.occupation.includes(dataItem.occupations)) return false;

                return true;
            })

            const filterDataOrder = []
            const columns = []
            yearArray.forEach((yearArrayItem, yearArrayIndex) => {
                for (let mes = 0; mes < 12; mes++) {

                    let somaIptu = 0
                    filterData.forEach(itemIptu => {
                        const itemDate = new Date(itemIptu.date);
                        if (itemDate.getMonth() === mes && itemDate.getFullYear() === yearArrayItem) {
                            somaIptu += +itemIptu.collected_value
                        }
                    })

                    if (yearArrayIndex === 0) {
                        filterDataOrder.push({ month: monthNames[mes], [`year_value_${yearArrayItem}`]: somaIptu })
                    } else {
                        filterDataOrder[mes] = { ...filterDataOrder[mes], [`year_value_${yearArrayItem}`]: somaIptu }
                    }
                }

                columns.push({
                    field: `year_value_${yearArrayItem}`,
                    name: yearArrayItem,
                })
            })

            // Anos selecionados
            const yearSelected = document.querySelector('#year-selected');
            yearSelected.innerHTML = "";

            yearArray.forEach(year => {
                const newHtml = `
                <div id="yearSelected_${year}" class="col-md-12">
                    <span class="btn btn-outline-secondary col-md-5 mr-2">${year}</span>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm col-md-3"
                        onclick="() => {console.log('remover' + year)}"
                        >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                `;
                yearSelected.innerHTML = yearSelected.innerHTML + newHtml;
            })

            chartPanel.update(chart, {
                columns,
                legend: "month",
                dataForChart: filterDataOrder,
                type: 'line'
            }
            )
        },

        addyearChart(chart, data) {
            const yearForAddChart = document.querySelector('#yearForAdd').value;
            if (chartIptu.month.yearSelected.includes(yearForAddChart)) return;
            chartIptu.month.yearSelected.push(yearForAddChart)

            chartIptu.month.update(chart, data)
        }
    },

    day: {
        store: {
            filter: {
                type: ['001', '002', '003', '004', '005'],
                occupation: ['001', '002', '003', '004', '005']
            },
            month: [1],
            year: [2021]
        },

        create(data, filter) {

            const dateOnce = [];

            const filterData = data.chart.dataForChart.filter(dataItem => {

                console.log(dataItem)

                const [yearItem, monthItem, dayItem] = dataItem.date.split("-");

                console.log(yearItem, monthItem, dayItem)

                if (!chartIptu.day.store.month.includes(dataItemDate.getMonth()+1)) return false;
                if (!chartIptu.day.store.year.includes(dataItemDate.getFullYear())) return false;

                console.log(dataItemDate);
                console.log(dataItemDate.getDay());
                console.log(dataItem.date);

                !dateOnce.includes(dataItemDate.getDay()) && dateOnce.push(dataItemDate.getDay())
                return true;
            })


            console.log(dateOnce);

            const filterDataOrder = [];



            console.log(filterData)

            // Anos selecionados
            const yearSelected = document.querySelector('#year-selected');
            yearArray.forEach(year => {
                const newHtml = `
                <div id="yearSelected_${year}" class="col-md-12">
                    <span class="btn btn-outline-secondary col-md-5 mr-2">${year}</span>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm col-md-3"
                        onclick="() => {console.log('remover' + year)}"
                        >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                `;
                yearSelected.innerHTML = newHtml;
            })

            //Prennce os Anos nos selects
            const falterEl = document.querySelector(data.filter.el);
            const options = []

            data.chart.dataForChart.forEach((dataItem, dataIndex) => {
                const dataItemDate = new Date(dataItem.date)
                if (!options.includes(dataItemDate.getFullYear())) {
                    options.push(dataItemDate.getFullYear())
                }
            })

            options.sort()

            options.forEach(option => {
                const createOptions = document.createElement('option');
                createOptions.value = option;
                createOptions.innerHTML = option;
                if (option === 2021) createOptions.selected = true;
                falterEl.appendChild(createOptions)
            })

            return chartPanel.create({
                columns: data.chart.columns,
                el: data.chart.el,
                legend: data.chart.legend,
                dataForChart: filterDataOrder,
                type: "line"
            })


        },

        update(chart, data) {
            const yearArray =
                Array.isArray(chartIptu.month.yearSelected) ?
                    chartIptu.month.yearSelected.map(item => +item) : [+chartIptu.month.yearSelected];
            yearArray.sort()

            const filterData = data.dataForChart.filter(dataItem => {
                const dataItemDate = new Date(dataItem.date);



                if (!yearArray.includes(dataItemDate.getFullYear())) return false;
                if (!chartIptu.month.filter.type.includes(dataItem.tipo)) return false;
                if (!chartIptu.month.filter.occupation.includes(dataItem.codigo_trs)) return false;

                return true;
            })

            const filterDataOrder = []
            const columns = []
            yearArray.forEach((yearArrayItem, yearArrayIndex) => {
                for (let mes = 0; mes < 12; mes++) {

                    let somaIptu = 0
                    filterData.forEach(itemIptu => {
                        const itemDate = new Date(itemIptu.date);
                        if (itemDate.getMonth() === mes && itemDate.getFullYear() === yearArrayItem) {
                            somaIptu += +itemIptu.collected_value
                        }
                    })

                    if (yearArrayIndex === 0) {
                        filterDataOrder.push({ month: monthNames[mes], [`year_value_${yearArrayItem}`]: somaIptu })
                    } else {
                        filterDataOrder[mes] = { ...filterDataOrder[mes], [`year_value_${yearArrayItem}`]: somaIptu }
                    }
                }

                columns.push({
                    field: `year_value_${yearArrayItem}`,
                    name: yearArrayItem,
                })
            })

            // Anos selecionados
            const yearSelected = document.querySelector('#year-selected');
            yearSelected.innerHTML = "";

            yearArray.forEach(year => {
                const newHtml = `
                <div id="yearSelected_${year}" class="col-md-12">
                    <span class="btn btn-outline-secondary col-md-5 mr-2">${year}</span>
                    <button
                        type="button"
                        class="btn btn-danger btn-sm col-md-3"
                        onclick="() => {console.log('remover' + year)}"
                        >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                `;
                yearSelected.innerHTML = yearSelected.innerHTML + newHtml;
            })

            chartPanel.update(chart, {
                columns,
                legend: "month",
                dataForChart: filterDataOrder,
                type: 'line'
            }
            )
        },

        addyearChart(chart, data) {
            const yearForAddChart = document.querySelector('#yearForAdd').value;
            if (chartIptu.month.yearSelected.includes(yearForAddChart)) return;
            chartIptu.month.yearSelected.push(yearForAddChart)

            chartIptu.month.update(chart, data)
        }
    }
}
