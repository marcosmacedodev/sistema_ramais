$.ajax({
    url: "../lib/api.php",
    type: "GET",
    success: function(data){                
        for(let i in data){
            $('#cartoes').append(`<div class="cartao cartao-${data[i].status}">
                                <div>${data[i].nome}</div>
                                <div>${data[i].agente}</div>
                                <span class="${data[i].status} icone-posicao"></span>
                              </div>`)
        }
        
    },
    error: function(){
        console.log("Errouu!")
    }
});