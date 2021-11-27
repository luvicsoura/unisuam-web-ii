import React from 'react';

export const LoginContext = React.createContext({
    authed: false,
    user: {},
    setAuthed: (authed:boolean) => {},
    setUser: (user:any) => {},
});