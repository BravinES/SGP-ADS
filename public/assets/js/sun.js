const sun = {
    date() {
        return {
            hoje: new Date(),
            dia: this.hoje.getDate(),
            mes: this.hoje.getMonth() + 1,
            ano: this.hoje.getFullYear(),
            hora: this.hoje.getHours(),
            minuto: this.hoje.getMinutes(),
            segundo: this.hoje.getSeconds(),
            diaSemana: this.hoje.getDay(),
            month: ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'],
        }
    },

    colors: ["#005b96", "#2b9600", "#808000", "#5F9EA0", "#87CEEB", "#F08080", "#20B2AA", "#008B8B", "#3CB371", "#FFA500", "#FF69B4", "#DA70D6", "#CD853F", "#BA55D3", "#FF4500", "#F0E68C", "#B0C4DE", "#FF0000", "#8B008B", "#DAA520", "#0000FF", "#9400D3", "#008000", "#F5DEB3", "#DDA0DD", "#9ACD32", "#40E0D0", "#006400", "#A52A2A", "#66CDAA", "#FA8072", "#FF00FF", "#6495ED", "#90EE90", "#556B2F", "#9932CC", "#B8860B", "#BC8F8F", "#228B22", "#7B68EE", "#87CEFA", "#0000CD", "#EE82EE", "#FF6347", "#4682B4", "#B0E0E6", "#D8BFD8", "#ADD8E6", "#CD5C5C", "#4B0082", "#483D8B", "#00FF7F", "#FFE4C4", "#D2B48C", "#A0522D", "#2F4F4F", "#4169E1", "#FF8C00", "#EEE8AA", "#BDB76B", "#FFC0CB", "#B22222", "#6959CD", "#1E90FF", "#FFD700", "#778899", "#D2691E", "#8B4513", "#FFE4B5", "#C71585", "#A020F0", "#FFB6C1", "#FFDAB9", "#F4A460", "#ADFF2F", "#00CED1", "#6B8E23", "#9370DB", "#000080", "#FAFAD2", "#DEB887", "#00008B", "#DB7093", "#800000", "#48D1CC", "#FFA07A", "#FFDEAD", "#6A5ACD", "#E9967A", "#191970", "#98FB98", "#32CD32", "#8FBC8F", "#FF7F50", "#008080", "#DC143C", "#708090", "#8A2BE2", "#00FF00", "#2E8B57", "#FF1493", "#8B0000", "#00FA9A", "#7CFC00"],
    color: {
        primary: "#005b96",
        secondary: "#2b9600"
    },

    localStorageExpires() {
        var toRemove = [],                      //Itens para serem removidos
            currentDate = new Date().getTime(); //Data atual em milissegundos

        for (var i = 0, j = localStorage.length; i < j; i++) {
            var key = localStorage.key(i),
                itemValue = localStorage.getItem(key);

            //Verifica se o formato do item para evitar conflitar com outras aplicações
            if (itemValue && /^\{(.*?)\}$/.test(itemValue)) {

                //Decodifica de volta para JSON
                var current = JSON.parse(itemValue);

                //Checa a chave expires do item especifico se for mais antigo que a data atual ele salva no array
                if (current.expires && current.expires <= currentDate) {
                    toRemove.push(key);
                }
            }
        }

        // Remove itens que já passaram do tempo
        // Se remover no primeiro loop isto poderia afetar a ordem,
        // pois quando se remove um item geralmente o objeto ou array são reordenados
        for (var i = toRemove.length - 1; i >= 0; i--) {
            localStorage.removeItem(toRemove[i]);
        }
    },

    /**
     * Função para adicionar itens no localStorage
     * @param {string} chave Chave que será usada para obter o valor posteriormente
     * @param {*} valor Quase qualquer tipo de valor pode ser adicionado, desde que não falhe no JSON.stringify
     * @param {number} minutos Tempo de vida do item
     */
    setLocalStorage(chave, valor, minutos) {
        var expirarem = new Date().getTime() + (60000 * minutos);

        localStorage.setItem(chave, JSON.stringify({
            "value": valor,
            "expires": expirarem
        }));
    },

    /**
     * Função para obter itens do localStorage que ainda não expiraram
     * @param {string} chave Chave para obter o valor associado
     * @return {*} Retorna qualquer valor, se o item tiver expirado irá retorna undefined
     */
    getLocalStorage(chave) {
        sun.localStorageExpires();//Limpa itens

        const itemValue = localStorage.getItem(chave);

        if (itemValue && /^\{(.*?)\}$/.test(itemValue)) {
            const current = JSON.parse(itemValue); //Decodifica de volta para JSON
            return current.value;
        }

        return false;
    },

    groupBy(array, key) {
        return xs.reduce(function (rv, x) {
            (rv[x[key]] = rv[x[key]] || []).push(x);
            return rv;
        }, {});
    },

    async ajaxWithCache(dataCache, url, time = 720) {
        if (sun.getLocalStorage(dataCache)) return sun.getLocalStorage(dataCache)

        const newDataCache = await fetch(url).then(res => res.json());
        sun.setLocalStorage(dataCache, newDataCache, time);
        return newDataCache
    },

    async getData(url) {
        const newDataCache = await fetch(url).then(res => res.json());
        return newDataCache
    },

    pause(seg) {
        return new Promise(resolve => setTimeout(resolve, seg * 1000));
    }


}

window.sun = sun;
window._ = sun;
