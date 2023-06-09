
import React from 'react';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import { Link } from 'react-router-dom'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {
    faSignOutAlt
} from '@fortawesome/free-solid-svg-icons'
import {
    faStore
} from '@fortawesome/free-solid-svg-icons'
import {
    faExchangeAlt
} from '@fortawesome/free-solid-svg-icons'

export const mainListItems = (
    <div>
        <Link to={'/estoque'}>
        <ListItem>
            <ListItemIcon>
                <FontAwesomeIcon style={{ color: '#444444' }} icon={faStore} size='lg' />
            </ListItemIcon>
            <ListItemText primary="Meu Estoque" />
        </ListItem></Link>
        <Link to={'/'} onClick={() => localStorage.clear()}>
            <ListItem>
                <ListItemIcon>
                    <FontAwesomeIcon style={{ color: '#444444' }} icon={faSignOutAlt} size='lg' />
                </ListItemIcon>
                <ListItemText primary="Sair" />
            </ListItem></Link>
        <ListItem>
            <ListItemText primary="Versão 1.0.33" />
        </ListItem>
        <ListItem>
            <ListItemText primary="Desenvolvido por DeD Studio" />
        </ListItem>

    </div>
);