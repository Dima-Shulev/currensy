class СurrencyDate {

    currensy;
    newCurrensy;
    options;
    chart;
    select;
    option;
    data;
    dataArr;
    arraysDb;

    constructor() {
        this.currensy = {};
        this.newCurrensy = {};
        this.select = document.querySelector("#select");
        this.dataArr = {};
        this.arraysDb = {};
    }


    chartsGoogle() {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(this.connectChart);
    }

    connectChart = () => {

        let socket = new WebSocket("ws://fx-gen.otv5125.ru");
        socket.onopen = function () {
            //alert("Соединение установлено.");
            setInterval(() => {
                socket.send(2);
            }, 5000)
        };
        socket.onclose = function (event) {
            if (event.wasClean) {
                //alert('Соединение закрыто чисто');
            } else {
               //alert('Обрыв соединения'); // например, "убит" процесс сервера
            }
            //alert('Код: ' + event.code + ' причина: ' + event.reason);
        };
        socket.onmessage = (event) => {
                this.parseDate(JSON.parse(event.data));
            socket.onerror = function (error) {
                alert("Ошибка " + error.message);
            };
        }
    }



    parseDate(bodyEvent) {
        if (!this.currensy[bodyEvent.name]){
            this.currensy[bodyEvent.name] = [];
              //this.ajax(bodyEvent.name,"insertDb.php","application/x-www-form-urlencoded");
            this.select.addEventListener("click", (event) => {
                if ((this.select.value === bodyEvent.name) && (event.type === "click")) {

                    this.getInterval(bodyEvent.name);
                    console.log(event.type);
                }
            });
        }
        this.selectForm(this.currensy);
        this.currensy[bodyEvent.name].push(bodyEvent.value);
        //this.ajax(this.currensy[bodyEvent.name],"insertDb.php","application/x-www-form-urlencoded");
        //console.log(this.currensy);

            if(this.select.value === bodyEvent.name) {
                 if (!this.dataArr[bodyEvent.name]) this.dataArr[bodyEvent.name] = [];
                 this.dataArr[bodyEvent.name].push(bodyEvent.value);
                 if (this.dataArr[bodyEvent.name].length !== this.currensy[bodyEvent.name].length) {
                     //console.log(this.dataArr);
                 this.getInterval(bodyEvent.name);
                 }
           }
    }

    ajax(bodyDate,url,ContentType){
        fetch(url,{
            method: "POST",
            headers: {
                "Content-Type": ContentType
            },
            body: JSON.stringify(bodyDate)
        }).then(response => response.json())
            .then(result => console.log(result))
    }



    getInterval(property){
        for (let i = 0; i < this.currensy[property].length; i++) {
                    this.data = google.visualization.arrayToDataTable([["Дата", property],
                        ["", this.currensy[property][i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]],
                        ["", this.currensy[property][++i]]
                    ]);
                   this.options = {
                       title: 'График котировок',
                       hAxis: {title: 'Year', titleTextStyle: {color: '#333'}},
                       vAxis: {minValue: 0}
                    };
                   this.chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                   this.chart.draw(this.data, this.options);

               }
     }
    selectForm(bodyEvent) {
        for (let property in bodyEvent) {
            if (!this.newCurrensy[property]) {
                if (property !== "datetime") {
                    this.newCurrensy[property] = bodyEvent[property];

                    this.option = document.createElement("option");
                    this.option.className = "option";
                    this.option.innerText = property;
                    this.select.append(this.option);
                }
            }

        }
    }
}