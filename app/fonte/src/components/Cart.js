import { Component } from 'react';
import Services from "../Services";

class CartInfo extends Component {

    state = {
        cart:0,
        action: 'list',}

        componentDidMount() {
            // document.getElementById("top").scroll(0, 0)
            let carrinho = localStorage.getItem('carrinho') || '';
            let usuario = JSON.parse(localStorage.getItem('user')) || '';
            let token = JSON.parse(localStorage.getItem('token')) || '';
    
            if (carrinho !== '') {
                const cart = [{
                    id_carrinho: carrinho,
                    id_produto: "",
                    qntd: "",
                    action: "list_produtos"
                }];
                Services.carrinho(this, usuario.id, token, cart[0]);
            }
        }

        // componentWillUnmount(){
        //     this.setState({cart : 0})
        // }

        responseCarrinho(response) {
            if (response.success === 'true') {
                if (this.state.action === 'list') {
                    this.setState({ cart: response.produtos.length })
                }
            }
            else {
                this.setState({cart:0})
            }
        }

    render() {
        return this.state.cart;
    }
}

export default CartInfo;