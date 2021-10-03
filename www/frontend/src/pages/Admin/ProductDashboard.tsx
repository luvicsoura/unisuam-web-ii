import AddIcon from '@mui/icons-material/Add';
import DeleteIcon from '@mui/icons-material/Delete';
import EditIcon from '@mui/icons-material/Edit';
import { ButtonGroup, Container, List, ListItem, ListItemText } from '@mui/material';
import CloseIcon from '@mui/icons-material/Close';
import IconButton from '@mui/material/IconButton';
import React, { Component } from 'react';
import Fab from '@mui/material/Fab';
import { httpClient } from '../../httpClient';
import { OriginalState, ProductEditForm, ProductEditFormState } from './ProductEditForm';

enum VIEWS {
    productList,
    createProduct,
    editProduct
}


export class ProductDashboard extends Component {

    state = {
        products: [],
        currentProduct: OriginalState,
        fetchProducts: true,
        view: VIEWS.productList
    }

    componentDidMount() {
        if (this.state.fetchProducts) this.getProducts();
    }

    componentDidUpdate() {
        if (this.state.fetchProducts) this.getProducts();
    }

    async getProducts() {


        const { data:products } = await httpClient.get('/product');

        this.setState({ 
            products: products,
            fetchProducts: false
        })
    }

    async deleteProduct(productSlug:string) {
        
        const response = await httpClient.delete(`/product/${productSlug}`);

        this.setState({ fetchProducts: true });
    }

    toggleCreate = () => {
        let view;

        switch (this.state.view) {
            
            case VIEWS.productList:
                view = VIEWS.createProduct;
                break;
            
            default:
                view = VIEWS.productList;
        }

        this.setState({ view });
    }

    editProduct = (product: any) => {
        this.setState({
            view: VIEWS.editProduct,
            currentProduct: product
        });
    }

    saveProduct = async (productData: any, resetForm: Function) => {

        const action = this.getProductHttpAction();
        const actionEndpoint = this.getActionEndpointFromProductData(productData);
        const response = await action(actionEndpoint, productData);

        this.afterSave();
        resetForm();
    }

    afterSave() {
        if (this.state.view === VIEWS.editProduct) this.toggleCreate();
        this.setState({ fetchProducts: true });
    }

    getProductHttpAction() {
        switch(this.state.view) {
            case VIEWS.editProduct:
                return httpClient.patch;
            case VIEWS.createProduct:
                return httpClient.post;

            default:
                return () => {}
        }
    }

    getActionEndpointFromProductData(productData: ProductEditFormState) {

        let endpoint = '/product';

        if (this.state.view === VIEWS.editProduct) {
            endpoint += `/${productData.slug}`
        }

        return endpoint;
    }

    render() {

        const components = [
            <Fab 
                color="primary"
                aria-label="add"
                onClick = {this.toggleCreate}
            >
                {this.state.view === VIEWS.productList ? 
                    <AddIcon /> : <CloseIcon/>
                }
            </Fab>
        ]

        // Mal-escrito
        switch (this.state.view) {

            case VIEWS.createProduct:
                components.push(
                    <Container>
                        <ProductEditForm
                            onSubmit = {this.saveProduct}
                            onCancel = {this.toggleCreate}
                        />
                    </Container>
                );
                break;

            case VIEWS.editProduct:
                components.push(
                    <Container>
                        <ProductEditForm
                            initialState = {this.state.currentProduct}
                            onSubmit = {this.saveProduct}
                            onCancel = {this.toggleCreate}
                        />
                    </Container>
                );
                break;

            case VIEWS.productList: 
                components.push(
                    <Container>
                        <List sx={{ width: '100%', maxWidth: 360, bgcolor: 'background.paper' }}>
                            {!this.state.products.length && (
                                <ListItem>
                                    <ListItemText 
                                        primary = {'Nenhum Produto Encontrado'} 
                                    />
                                </ListItem>
                            )}

                            {this.state.products.map((product:any, key) => (
                                <ListItem
                                    key={key}
                                    secondaryAction={
                                        <ButtonGroup>
                                            <IconButton 
                                                edge="end" 
                                                aria-label="edit"
                                                onClick = {() => this.editProduct(product)}
                                            >
                                                <EditIcon />
                                            </IconButton>
                                            <IconButton 
                                                edge="end"
                                                aria-label="delete"
                                                onClick = {() => this.deleteProduct(product.slug)}
                                            >
                                                <DeleteIcon />
                                            </IconButton>
                                        </ButtonGroup>
                                    }
                                >
                                    <ListItemText 
                                        primary = {product.name} 
                                        secondary = {product.description.substring(0, 90) + '...'}
                                    />
                                </ListItem>
                            ))}
                        </List>
                    </Container>
                );
                break;
        }

        return components.reverse();
    }
}