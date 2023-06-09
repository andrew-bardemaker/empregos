
import React from 'react';
import ListItem from '@material-ui/core/ListItem';
import ListItemIcon from '@material-ui/core/ListItemIcon';
import ListItemText from '@material-ui/core/ListItemText';
import { Link } from 'react-router-dom'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faBeer, faPercentage, faQuestion, faInfo, faUser, faPlus, faHome, faNewspaper } from '@fortawesome/free-solid-svg-icons'
import { faWhatsapp } from '@fortawesome/free-brands-svg-icons'

export const mainListItems = (
  <div>
    <Link to={'/home'}>
        <ListItem>
      <ListItemIcon>
        <FontAwesomeIcon style={{color:'#444444'}} icon={faHome} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Home" />
    </ListItem>
    </Link>
    <Link to={'/faq'}><ListItem button>
      <ListItemIcon>
        <FontAwesomeIcon style={{color:'#444444'}} icon={faQuestion} size='lg' />
      </ListItemIcon>
      <ListItemText primary="FAQ" />
    </ListItem></Link>
    <Link to={'/ajuda'}><ListItem button>
      <ListItemIcon>
        <FontAwesomeIcon style={{color:'#444444'}} icon={faInfo} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Ajuda" />
    </ListItem></Link>
    <Link to={'/login'}>
    <ListItem>
      <ListItemIcon>
        <FontAwesomeIcon style={{color:'#444444'}} icon={faUser} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Entrar" />
    </ListItem>
    </Link>
    <Link to={'/cadastro'}>
    <ListItem>
      <ListItemIcon>
        <FontAwesomeIcon style={{color:'#444444'}} icon={faPlus} size='lg' />
      </ListItemIcon>
      <ListItemText primary="Cadastrar" />
    </ListItem>
    </Link>
    <ListItem>
      <ListItemText primary="Versão 1.0.33" /><br/>
    </ListItem>
    <ListItem sx={{bottom: 0}}>
      <ListItemText primary="Desenvolvido por DeD Studio" />
    </ListItem>
  </div>
);