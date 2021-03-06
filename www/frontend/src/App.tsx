import { createTheme, ThemeProvider } from '@mui/material/styles';
import * as React from 'react';
import {
  BrowserRouter as Router, Route, Switch
} from "react-router-dom";
import { LoginContext } from './contexts/Login';
import './App.css';
import { AdminPage }  from './pages/Admin';
import { ProductListing } from './pages/ProductList';

const theme = createTheme();
export default class App extends React.Component {
  state = {
    authed: false,
    user: {},
  }

  render() {
    return (
      <LoginContext.Provider value={{
        authed: this.state.authed, 
        user: this.state.user,
        setAuthed: (authed) => this.setState({ authed }),
        setUser: (user) => this.setState({ user })
      }}>
        <ThemeProvider theme={theme}>
          <Router>
            <Switch>
              <Route exact path="/admin">
                <AdminPage/>
              </Route>
              <Route exact path="/">
                <ProductListing/>
              </Route>
            </Switch>
          </Router>
        </ThemeProvider>
      </LoginContext.Provider>
    )
  }
}