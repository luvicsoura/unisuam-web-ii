import ShoppingCartIcon from '@mui/icons-material/ShoppingCart';
import { Alert, Button, Dialog, DialogActions, DialogContent, DialogContentText, DialogTitle, Fab, List, ListItem, ListItemText, Snackbar } from '@mui/material';
import React, { Component } from 'react';
import { httpClient } from '../../httpClient';
import { ProductListItem } from './ProductListItem';


export class ProductListing extends Component {

    state = {
        products: [],
        cartItems: [],
        showDialog: false,
        showSnackBar: false,
        fetchProducts: true
    }

    componentDidMount() {
        this.getProducts();
    }

    componentDidUpdate() {
        this.getProducts();
    }

    async getProducts() {

        if (!this.state.fetchProducts) return;

        const { data:products } = await httpClient.get('/product');

        this.setState({ 
            products: products.filter((product:any) => product.quantity > 0),
            fetchProducts: false
        })
    }

    addToCart = (product: any) => {

        if (!product.quantity) return;

        this.setState({
            cartItems: [...this.state.cartItems, product ],
            showSnackBar: true
        })
    }

    buy = async () => {

        if (!this.state.cartItems.length) return;

        const response = await httpClient.post('/order', this.state.cartItems);
        this.setState({ showDialog: true, fetchProducts: true, cartItems: [] });
    }

    handleSnackbarClose = () => {
        this.setState({ showSnackBar: false });
    }

    render() {

        return (
            <>
                <List sx={{ width: '100%', bgcolor: 'background.paper' }}>
                    {!this.state.products.length && (
                        <ListItem>
                            <ListItemText 
                                primary = {'Nenhum Produto Encontrado'} 
                            />
                        </ListItem>
                    )}

                    {this.state.products.map((product:any) => (
                        <ProductListItem 
                            key = {product.id}
                            product = {product}
                            onAddToCart = {this.addToCart}
                        />
                    ))}
                </List>
                <Fab
                    variant="extended"
                    onClick={this.buy}
                >
                    Finalizar Comprar
                    <ShoppingCartIcon />
                </Fab>
                <Snackbar 
                    open={this.state.showSnackBar}
                    autoHideDuration={3000}
                    anchorOrigin={{
                        vertical: 'bottom',
                        horizontal: 'right'
                    }}
                    onClose={this.handleSnackbarClose}
                >
                    <Alert onClose={this.handleSnackbarClose} severity="success" sx={{ width: '100%' }}>
                        Item adicionado ao carrinho
                    </Alert>
                </Snackbar>
                <Dialog
                    open={this.state.showDialog}
                    onClose={() => this.setState({ showDialog: false })}
                    aria-labelledby="alert-dialog-title"
                    aria-describedby="alert-dialog-description"
                >
                    <DialogTitle id="alert-dialog-title">
                        Pedido efetuado!
                    </DialogTitle>
                    <DialogContent>
                    <DialogContentText id="alert-dialog-description">
                        Seu pedido foi efetuado.
                    </DialogContentText>
                    </DialogContent>
                    <DialogActions>
                        <Button onClick={() => this.setState({ showDialog: false })} autoFocus>
                            Ok
                        </Button>
                    </DialogActions>
                </Dialog>
            </>
            
        )
    }
}