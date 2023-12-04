const isup = {
    sel: document.querySelector.bind(document),
    html: {
        chart: {
            da: {
                el: document.querySelector('#chartyearDa') || false,
                start: document.querySelector('#yearStartChartDa') || false,
                end: document.querySelector('#yearEndChartDa') || false,
                url: () => isup.html.chart.da.el.getAttribute('data-url')
            },
            iptu: {
                el: document.querySelector('#chartyearIptu') || false,
                start: document.querySelector('#yearStartChartIptu') || false,
                end: document.querySelector('#yearEndChartIptu') || false,
                url: () => isup.html.chart.iptu.el.getAttribute('data-url')
            },
            iss: {
                el: document.querySelector('#chartyearIss') || false,
                start: document.querySelector('#yearStartChartIss') || false,
                end: document.querySelector('#yearEndChartIss') || false,
                url: () => isup.html.chart.iss.el.getAttribute('data-url')
            }
        }
    },

    setInputDate(el, data, fnc) {
        if (el.start && el.end) {
            data.forEach(year => {
                const createOptions = document.createElement('option');
                createOptions.value = year;
                createOptions.innerHTML = year;
                if(year === 2015) createOptions.selected = true;
                el.start.appendChild(createOptions)
            })

            data.forEach(year => {
                const createOptions = document.createElement('option');
                createOptions.value = year;
                createOptions.innerHTML = year;
                if(year === 2021) createOptions.selected = true;
                el.end.appendChild(createOptions)
            })

            el.start.addEventListener('change', fnc)
            el.end.addEventListener('change', fnc)
        }
    },

    chartBarUpdade(el, data, elChart) {
        const yearStart = el.start && JSON.parse(el.start.value.toLowerCase()) || _data.today.getFullYear() - 6;
        const yearEnd = el.end && JSON.parse(el.end.value.toLowerCase()) || _data.today.getFullYear();

        let yearChart = yearStart <= yearEnd ?
        { start: yearStart, end: yearEnd } :
        { start: yearEnd, end: yearStart }

        let dataChart = { labels: [], columns: [[], []] }

        data.labels.forEach((label, index) => {
            if (label >= yearChart.start && label <= yearChart.end) {
                dataChart.labels.push(label);

                for (let i = 0; i < data.columns.length; i++) {
                    dataChart.columns[i][dataChart.labels.length - 1] = data.columns[i][index];
                }
            }
        })

        elChart.data.labels = dataChart.labels;
        elChart.data.datasets.forEach((dataset, index) => {
            dataset.data = dataChart.columns[index]
        });

        elChart.update();
    },

    chartBarCreate(el, originaldata) {
        if (!el.el) return false;

        const yearStart = el.start && JSON.parse(el.start.value.toLowerCase()) || _data.today.getFullYear() - 6;
        const yearEnd = el.end && JSON.parse(el.end.value.toLowerCase()) || _data.today.getFullYear();

        let yearChart = yearStart <= yearEnd ?
            { start: yearStart, end: yearEnd } :
            { start: yearEnd, end: yearStart }

        let dataChart = { labels: [], columns: [[], []] }

        originaldata.labels.forEach((label, index) => {
            if (label >= yearChart.start && label <= yearChart.end) {
                dataChart.labels.push(label);

                for (let i = 0; i < originaldata.columns.length; i++) {
                    dataChart.columns[i][dataChart.labels.length - 1] = originaldata.columns[i][index];
                }
            }
        })

        var lastHoveredIndex = null

        return new Chart(el.el.getContext("2d"), {
            type: 'bar',
            data: {
                labels: dataChart.labels,
                datasets: [
                    {
                        label: "Lançado",
                        backgroundColor: "#005b96",
                        data: dataChart.columns[0]
                    },
                    {
                        label: "Arrecadado",
                        backgroundColor: "#2b9600",
                        data: dataChart.columns[1]
                    },
                ]
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
                onClick: function(e, items) {
                    if(!el.url()) return;
                    if ( items.length == 0 ) return; //Clicked outside any bar.
                    window.location.href = el.url();
                }
           },

        })
    },

    chartBarCreateGeneral(el, chartData) {
        if (!el.el) return false;

        return new Chart(el.el.getContext("2d"), {
            type: 'line',
            data: chartData,

            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
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

                options: { },

            }

        })
    }
}

// Cria os Graficos
const chart = {
    bar: {
        da: isup.chartBarCreate(isup.html.chart.da, _data.da),
        iptu: isup.chartBarCreate(isup.html.chart.iptu, _data.iptu),
        iptuMonth: chartMonthAndDay(2, [2017,2018,2019, 2020, 2021]),
        iss: isup.chartBarCreate(isup.html.chart.iss, _data.iss),
    }
}

// Preenche as data no imput
isup.setInputDate(
    isup.html.chart.da,
    _data.da.labels,
    () => isup.chartBarUpdade(
        isup.html.chart.da,
        _data.da, chart.bar.da)
);

isup.setInputDate(
    isup.html.chart.iptu,
    _data.iptu.labels,
    () => isup.chartBarUpdade(
        isup.html.chart.iptu,
        _data.iptu, chart.bar.iptu)
);

isup.setInputDate(
    isup.html.chart.iss,
    _data.iss.labels,
    () => isup.chartBarUpdade(
        isup.html.chart.iss,
        _data.iss, chart.bar.iss)
);

/* ------------------------------------------------------------------ */

function chartMonthAndDay(iptuChartMonth, iptuChartyear) {
    const iptuChartData = iptuDay.filter(data => {
        const dateItem = new Date(data.data_credito);
        return (dateItem.getMonth() + 1 === iptuChartMonth) && iptuChartyear.includes(dateItem.getFullYear())
    })

    const labels = () => {
        let dayAsc = [];

        iptuChartData.forEach(itemData => {
            const dateItem = new Date(itemData.data_credito);
            if (!dayAsc.includes(dateItem.getDate())) dayAsc.push(dateItem.getDate());
        })

        dayAsc.sort((a, b)=> a - b);

        dayLabel = dayAsc.map(label => {
            return `${label < 10 ? "0" + label : label}/${monthNames[iptuChartMonth-1]}`;
        })

        return {dayLabel, dayAsc}
    }

    const datasets = [];


    iptuChartyear.forEach((year, index) => {
        const columns = [];
        const dataColumns = [];

        iptuChartData.forEach(itemDate => {
            const dateItem = new Date(itemDate.data_credito);
            if (dateItem.getFullYear() === year) {
                dataColumns[dateItem.getDate()] = (+itemDate.value)
            }
        })

        labels().dayAsc.forEach(day => {
            columns.push(dataColumns[day] ? dataColumns[day] : 0)
        })

        datasets.push(
            {
                label: year,
                backgroundColor: "transparent",
                borderColor: chartColors[index],
                data: columns
            }
        )
    })

    return isup.chartBarCreateGeneral(
        { el: document.querySelector('#chartMonthIptu') },
        { labels: labels().dayLabel, datasets });
}
