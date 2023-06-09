
import React from 'react';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import { Link } from 'react-router-dom'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {
  faBeer, faPercentage, faQuestion, faInfo,
  faHome, faFileAlt, faMapMarked, faBullhorn, faSignOutAlt, faNewspaper, faTruck
} from '@fortawesome/free-solid-svg-icons'
import { faWhatsapp } from '@fortawesome/free-brands-svg-icons'

export const mainListItems = (
  <div>
    <Link to={'/home'}>
      <ListItem>
        <ListItemIcon>
          <FontAwesomeIcon style={{ color: '#444444' }} icon={faHome} size='lg' />
        </ListItemIcon>
        <ListItemText primary="Home" />
      </ListItem>
    </Link>
    <Link to={'/pedidos'}>
      <ListItem button>
        <ListItemIcon>
          <FontAwesomeIcon style={{ color: '#444444' }} icon={faTruck} size='lg' />
        </ListItemIcon>
        <ListItemText primary="Meus Envios" />
      </ListItem>
    </Link>
    <Link to={'/enderecos'}><ListItem button>
      <ListItemIcon>
        <FontAwesomeIcon style={{ color: '#444444' }} icon={faMapMarked} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Meus Endereços" />
    </ListItem></Link>
    <Link to={'/chamados'}>
      <ListItem>
        <ListItemIcon>
          <FontAwesomeIcon style={{ color: '#444444' }} icon={faBullhorn} size='lg' />
        </ListItemIcon>
        <ListItemText primary="Chamados" />
      </ListItem>
    </Link>
    <Link to={'/faq'}><ListItem button>
      <ListItemIcon>
        <FontAwesomeIcon style={{ color: '#444444' }} icon={faQuestion} size='lg' />
      </ListItemIcon>
      <ListItemText primary="FAQ" />
    </ListItem></Link>
    <Link to={'/ajuda'}><ListItem button>
      <ListItemIcon>
        <FontAwesomeIcon style={{ color: '#444444' }} icon={faInfo} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Ajuda" />
    </ListItem></Link>
    <Link to={'/'} onClick={() => {
      localStorage.clear(); if (window.cordova) {
        window.plugins.OneSignal.setSubscription(false)
      }
    }}>
      <ListItem>
        <ListItemIcon>
          <FontAwesomeIcon style={{ color: '#444444' }} icon={faSignOutAlt} size='lg' />
        </ListItemIcon>
        <ListItemText primary="Sair" />
      </ListItem>
    </Link>
    <ListItem>
      <ListItemText primary="Versão 0.0.1" />
    </ListItem>
    <ListItem>
      <ListItemText primary="Desenvolvido por DeD Studio" />
    </ListItem>
  </div>
);