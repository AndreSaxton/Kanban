$(document).ready(function () {

    $.ajax({
        method: "POST",
        url: "controller.php",
        data: {action: 'showColluns'},
        success: function(response){
            //console.log(response);
            var j = JSON.parse(response);
            console.log(j);
            createCollums(j);
        }
    })
    .fail(function (response){
        console.log(response);
    })

    function createTasks(divTasks, ts) {
        //console.log(divTasks);
        //console.log(ts);
        for (let index = 0; index < Object(ts).length; index++) {
                        
            let task = document.createElement("div");
            let taskName = document.createElement("p");
            let taskResponsable = document.createElement("p");
            let inputTaskName = document.createElement("input");
            let inputTaskResponsable = document.createElement("input");
            let taskImgEdit = document.createElement("img");

            $(task).addClass("task");
            $(taskImgEdit).addClass("btnImg btnEditTask");
            $(inputTaskName).addClass("inputHidden");
            $(inputTaskName).addClass("inputTaskName");
            $(inputTaskResponsable).addClass("inputHidden");
            $(inputTaskResponsable).addClass("inputTaskResponsable");
            $(taskName).addClass("taskName");
            $(taskResponsable).addClass("taskResponsable");

            $(taskImgEdit).attr("src", "_img/edit-icon.png");

            $(divTasks).append(task);
            $(task).append(taskName);
            $(task).append(taskResponsable);
            $(task).append(inputTaskName);
            $(task).append(inputTaskResponsable);
            $(task).append(taskImgEdit);

            $(taskName).text(ts[index].nm_task);
            $(taskResponsable).text(ts[index].nm_responsable);
        }
    }

    function createCollums(collums){
        for (let index = 0; index < collums.length ; index++) {
            let col = document.createElement("div");
            let header = document.createElement("div");
            let headerTaskQuant = document.createElement("p");
            let headerP = document.createElement("p");
            let headerInput = document.createElement("input");
            let headerImgEdit = document.createElement("img");
            let headerImgAdd = document.createElement("img");
            let divTasks = document.createElement("div");

            $(col).addClass("collum");
            $(header).addClass("collum-header");
            $(headerTaskQuant).addClass("pQuantTask");
            $(headerP).addClass("pTitleCollum");
            $(headerInput).addClass("inputHidden inputTitleCollum");
            $(headerImgEdit).addClass("btnImg btnEditTitle");
            $(headerImgAdd).addClass("btnImg btnAddTask");
            $(divTasks).addClass("tasks");

            $(headerInput).attr("type", "text");
            $(headerImgEdit).attr("src", "_img/edit-icon.png");
            $(headerImgAdd).attr("src", "_img/add-icon.png");
        
            $(".container").append(col);
            $(col).append(header);
            $(col).append(divTasks);
            $(header).append(headerTaskQuant);
            $(header).append(headerP);
            $(header).append(headerInput);
            $(header).append(headerImgEdit);
            $(header).append(headerImgAdd);
            
            $(headerP).text(collums[index].nm_collum);
            let tasks = collums[index].task;
            if(tasks){
                createTasks(divTasks, tasks);
            }
            taskAddSortable();
        }
        countTasks();
    }

    $(document).on("click", ".btnEditTask", function (e) {
        let task = $(e.target).parent();
        console.log(task);
        let taskName = $(task).children(".taskName");
        let taskResponsable = $(task).children(".taskResponsable");
        let inputTaskName = $(task).children(".inputTaskName");
        let inputTaskResponsable = $(task).children(".inputTaskResponsable");

        if (taskName.css("display") == "none") {
            taskName.text(inputTaskName.val());
            taskResponsable.text(inputTaskResponsable.val());
        } else {
            inputTaskName.val(taskName.text());
            inputTaskResponsable.val(taskResponsable.text());
        }
        taskName.toggle();
        taskResponsable.toggle();
        inputTaskName.toggle();
        inputTaskResponsable.toggle();
    });

    $(document).on("click", "#btnSalvar", function (e) {
        let cols = $(".container").children(".collum");
        let arrayKanban = [];

        for (let index = 0; index < cols.length; index++) {
            let col = cols[index];
            let collumHeader = $(col).children(".collum-header");
            let divTasks = $(col).children(".tasks");
            let collumTitle = collumHeader.children(".pTitleCollum").text();
            let tasks = divTasks.children(".task");

            arrayKanban[index] = {};
            arrayKanban[index]["nm_collum"] = collumTitle;
            arrayKanban[index]["cd_order_collum"] = index+1;
            
            if(tasks){
                arrayKanban[index]["task"] = [];
                for (let index2 = 0; index2 < tasks.length; index2++) {
                    let task = tasks[index2];
                    let taskName = $(task).children(".taskName").text();
                    let taskResponsable = $(task).children(".taskResponsable").text();
                    
                    arrayKanban[index]["task"][index2] = {};
                    arrayKanban[index]["task"][index2]["cd_order_task"] = index2+1;
                    arrayKanban[index]["task"][index2]["nm_task"] = taskName;
                    arrayKanban[index]["task"][index2]["nm_responsable"] = taskResponsable;
                }
            }
        }
        let j = JSON.stringify(arrayKanban);
        //console.log(j);
        
        $.ajax({
            method: "POST",
            url: "controller.php",
            data: {action: 'saveKanban', kanban: j},
            success: function(response){
                console.log(response);
            }
        })
        .fail(function (response){
            console.log(response);
        })
    })

    addBtnsHeader();
    function addBtnsHeader() {
        $(document).on("click", ".btnEditTitle", function (e) {
            let collumHeader = $(e.target).parent();
            console.log(e.target);
            console.log(collumHeader);
            let a = collumHeader.children(1).children("p.pTitleCollum");
            console.log(a);
            
            
            if ($(collumHeader).children(".pTitleCollum").css("display") == "none") {
                $(collumHeader).children(".pTitleCollum").text($(collumHeader).children(".inputTitleCollum").val());
            } else {
                $(collumHeader).children(".inputTitleCollum").val($(collumHeader).children(".pTitleCollum").text());
            }
            $(collumHeader).children(".inputTitleCollum").toggle();
            $(collumHeader).children(".pTitleCollum").toggle();
        });
    
        $(document).on("click", ".btnAddTask", function (e) {
            let divTasks = $(e.target).parent().parent().children(".tasks")[0];
            tasks = [{
                nm_task: "New Task",
                nm_responsable: "Responsable"
            }];
            createTasks(divTasks ,tasks);
            countTasks();
        });
    }
    
    function countTasks(){
        let cont = $(".container");
        let cols = cont.children();
        //console.log(cols);
        //console.log(cont.children().length);
        for (let index = 0; index < cols.length ; index++) {
            //console.log(cols[index]);
            let tasks = $(cols[index]).children(".tasks");
            //console.log(tasks);
            let numTasks = tasks.children().length;
            let pQuantTask = $(cols[index]).children(".collum-header").children(".pQuantTask");
            $(pQuantTask).text(numTasks);
        }
    }

    $(".container").sortable({
        //appendTo: document.body
    });

    function taskAddSortable() {
        $(".tasks").sortable({
            //appendTo: $(".container")
            connectWith: ".tasks",
            receive: function (event, ui){
                countTasks();
            }
        });
    }
});