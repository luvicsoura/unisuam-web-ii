import { Button, FilledInput, FormControl, Grid, InputAdornment, InputLabel, TextField } from '@mui/material';
import React, { Component, FormEventHandler } from 'react';


type ProductEditFormProps = typeof ProductEditForm.defaultProps & {
}

export type ProductEditFormState = {
    name: string,
    slug: string,
    price: number,
    quantity: number,
    description: string
}

export const OriginalState: ProductEditFormState = {
    name: '',
    slug: '',
    price: 0,
    quantity: 0,
    description: ''
}

export class ProductEditForm extends Component<ProductEditFormProps> {
    
    static defaultProps = {
        onSubmit: (state: ProductEditFormState, resetForm: Function) => {},
        onCancel: () => {},
        initialState: {...OriginalState}
    }
   
    state: ProductEditFormState = {
        ...OriginalState
    }

    constructor(props: ProductEditFormProps) {
        super(props);

        this.state = {...props.initialState};
    }   

    handleSubmit:FormEventHandler = (e) => {
        e.preventDefault();
        this.props.onSubmit({...this.state}, this.resetState);
    }

    updateField = (field:string, value:any) => {
        this.setState( {[field]: value });
    }

    handleCancel = () => {
        this.resetState();
        this.props.onCancel();
    }

    resetState = () => {
        this.setState({ ...OriginalState });
    }

    render = () => (
        <form onSubmit={this.handleSubmit}>
            <Grid container spacing={2}>
                <TextField
                    required
                    id="filled-required"
                    label="Nome"
                    variant="filled"
                    onChange = {(event) => this.updateField('name', event.target.value)}
                    value = {this.state.name}
                />
                <TextField
                    id="filled-multiline-static"
                    label="Descrição"
                    multiline
                    value = {this.state.description}
                    onChange = {(event) => this.updateField('description', event.target.value)}
                    variant="filled"
                    rows={4}
                />
                <TextField
                    label="Quantidade"
                    id="filled-start-adornment"
                    sx={{ m: 1, width: '25ch' }}
                    type="number"
                        inputProps= {{
                            min: 0
                        }}
                    value = { this.state.quantity }
                    onChange = {(event) => this.updateField('quantity', event.target.value)}
                    InputProps={{
                        endAdornment: <InputAdornment position="end">Un</InputAdornment>,
                    }}
                    variant="filled"
                />
                <FormControl fullWidth sx={{ m: 1 }} variant="filled">
                    <InputLabel htmlFor="filled-adornment-amount">Preço</InputLabel>
                    <FilledInput
                        id="filled-adornment-amount"
                        value={this.state.price}
                        type="number"
                        inputProps= {{
                            min: 0
                        }}
                        onChange={(event) => { this.updateField('price', event.target.value as unknown as number)}}
                        startAdornment={<InputAdornment position="start">$</InputAdornment>}
                    />
                </FormControl>
            </Grid>
            
            <Grid container spacing={2}>
                <Grid item>
                    <Button 
                        variant="text"
                        onClick = {this.handleCancel}
                    >
                        Cancelar
                    </Button>
                </Grid>
                <Grid item>
                    <Button 
                        type="submit"
                        variant="contained"
                    >
                        Salvar
                    </Button>
                </Grid>
            </Grid>
        </form>
    )
}