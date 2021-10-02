import React from 'react';

export const LoginContext = React.createContext({
    authed: false,
    setAuthed: (authed:boolean) => {}
});