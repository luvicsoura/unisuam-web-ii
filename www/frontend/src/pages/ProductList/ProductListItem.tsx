import { Component } from 'react';
import { ListItem, ButtonGroup, TextField, InputAdornment, Button, ListItemText } from '@mui/material';
import AddShoppingCartIcon from '@mui/icons-material/AddShoppingCart';

interface ProductListItemProps {
    product: any,
    onAddToCart: Function
}

export class ProductListItem extends Component<ProductListItemProps> {

    state = {
        quantity: 0,
        productId: 0
    }

    constructor(props: ProductListItemProps) {
        super(props);

        this.state.productId = parseInt(props.product.id);
    }

    updateField = (field:string , value:number) => {
        this.setState({ [field]: value });
    }

    handleAddToCart = () => {
        this.props.onAddToCart({
            ...this.props.product,
            quantity: this.state.quantity,
        });
    }
    
    render = () => (
        <ListItem
            secondaryAction={
                <ButtonGroup>
                    <TextField
                        label="Quantidade"
                        id="filled-start-adornment"
                        sx={{ m: 1, width: '25ch' }}
                        type="number"
                            inputProps= {{
                                min: 0
                            }}
                        value = { this.state.quantity }
                        onChange = {(event) => this.updateField('quantity', parseInt(event.target.value))}
                        InputProps={{
                            endAdornment: <InputAdornment position="end">Un</InputAdornment>,
                        }}
                        variant="filled"
                    />
                    <Button 
                        variant="contained" 
                        onClick = {this.handleAddToCart}
                        endIcon={<AddShoppingCartIcon />}
                    >
                        Adicionar
                    </Button>
                </ButtonGroup>
            }
        >
            <ListItemText 
                primary = {this.props.product.name} 
                secondary = {this.props.product?.description ? this.props.product.description.substring(0, 90) + '...' : ''}
            />
        </ListItem>
    )
}