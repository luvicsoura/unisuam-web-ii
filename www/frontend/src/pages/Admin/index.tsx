import React, { Component } from 'react';
import { LoginContext } from '../../contexts/Login';
import { ProductDashboard } from './ProductDashboard';
import SignIn from './SignIn';

export class AdminPage extends Component {
    render() {
        console.log('context', this.context);
        return (
            this.context.authed ? <ProductDashboard /> : <SignIn />
        );
    }
}

AdminPage.contextType = LoginContext;