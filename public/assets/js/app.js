
// ===============================================================================
function adicionar_carrinho(id_produto) {
    
    // adicionar produto ao carrinho
    axios.defaults.withCredentials = true
    axios.get('?a=adicionar_carrinho&id_produto=' + id_produto)
        .then(function(response) {

            var total_produtos = response.data
            document.getElementById('carrinho').innerText = total_produtos

            console.log(response.data)

        })
    
}

// ===============================================================================
function limpar_carrinho() {
    
    var e = document.getElementById('confirmar_limpar_carrinho')
    e.style.display = 'inline'

}

function limpar_carrinho_off() {
    var e = document.getElementById('confirmar_limpar_carrinho')
    e.style.display = 'none'
}