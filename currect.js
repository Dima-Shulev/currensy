class СurrencyDate {

    arrays;
    currensy;
    options;
    chart;
    select;
    option;
    newCurrensy;
    data;
    dateTimes;

    constructor() {
        this.arrays = [];
        this.currensy = {};
        this.newCurrensy = {};
        this.select = document.querySelector("#select");
    }


    chartsGoogle() {
        google.charts.load("current", {packages: ["corechart"]});
        google.charts.setOnLoadCallback(this.connectChart);
    }

    connectChart = () => {

        let socket = new WebSocket("ws://fx-gen.otv5125.ru");
        socket.onopen = function () {
            alert("Соединение установлено.");
            setInterval(() => {
                socket.send(2);
            }, 1000)
        };
        socket.onclose = function (event) {
            if (event.wasClean) {
                alert('Соединение закрыто чисто');
            } else {
               alert('Обрыв соединения'); // например, "убит" процесс сервера
            }
            alert('Код: ' + event.code + ' причина: ' + event.reason);
        };

        socket.onmessage = (event) => {
            let body = JSON.parse(event.data);
            if(body !== JSON.parse(event.data)){
                this.getDate(JSON.parse(event.data));
            }


            socket.onerror = function (error) {
                alert("Ошибка " + error.message);
            };
        }
    }

    getDate(bodyEvent) {

        if (!this.currensy[bodyEvent.name]) this.currensy[bodyEvent.name] = [];
        let dates = new Date();
        let option = { weekday: 'short'};
        let dateTimes = new Intl.DateTimeFormat('ru-RU', option).format(dates)+" "+dates.getHours()+":"+dates.getMinutes()+":"+dates.getSeconds();
        this.dateTimes = new Intl.DateTimeFormat('ru-RU',option).format(dates)+ " "+dates.getHours()+":"+dates.getMinutes();
        if (!this.currensy.datetime)this.currensy.datetime = [];
        this.currensy.datetime.push(dateTimes);

        //this.currensy[bodyEvent.name].push(dateTimes + " - " +bodyEvent.value);//добавление даты в значение с разделителем -
        //if(!this.currensy[bodyEvent.name][dateTimes])this.currensy[bodyEvent.name][dateTimes] = bodyEvent.value; //вначале хотел сделать ключом дату от котировки, но возникла проблема с перебором ключей в цикле
        if(this.currensy[bodyEvent.name])this.currensy[bodyEvent.name].push(bodyEvent.value);
        console.log(this.currensy);
        //this.ajax(this.currensy,"insertDb.php","application/x-www-form-urlencoded");
        this.selectForm(this.currensy);
    }

   /*ajax(bodyDate,url,ContentType){
      fetch(url,{
          method: "POST",
          headers: {
              "Content-Type": ContentType
          },
          body: bodyDate
      }).then(response=>response.json())
          .then(result=>alert(result))
   }*/


    selectForm(bodyEvent) {

        for (let property in bodyEvent) {
            if (!this.newCurrensy[property]) {
                if (property !== "datetime") {
                    this.newCurrensy[property] = bodyEvent[property];
                    this.option = document.createElement("option");
                    this.option.className = "option";
                    this.option.innerText = property;
                    this.select.append(this.option);

                    this.select.addEventListener("click", () => {
                        setInterval(() => {
                            if (this.select.value === property) {
                                for (let i = 0; i < this.newCurrensy[property].length; i++) {
                                    this.data = google.visualization.arrayToDataTable([["Дата", property],
                                        ["", this.newCurrensy[property][i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]],
                                        ["", this.newCurrensy[property][++i]]
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
                        }, 1000);

                    });
                }
            }
        }
    }
}
