import * as React from 'react';
import Typography from '@mui/material/Typography';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemText from '@mui/material/ListItemText';
import Grid from '@mui/material/Grid';
import { Divider } from '@material-ui/core';

const products = [
  {
    name: 'Envelopes',
    desc: 'Next Day',
    price: 'R$56,32',
  },
];

const addresses = ['Rua Felipe Camarão', '751', 'Sala 405', 'Bom Fim', 'Porto Alegre, RS'];

export default function Review() {
  return (
    <React.Fragment>
      <Typography variant="h6" gutterBottom>
        Revise seu envio
      </Typography>
      <Typography variant="p" color="#ff8a00" gutterBottom>
        Revise a descrição de seu envio
      </Typography>
      <Grid container spacing={3} paddingTop={2}>
        <Grid item xs={12}>
          <Divider />
        </Grid>
        <Grid item xs={12} sm={6}>
          <Typography variant="h6" gutterBottom >
            Remetente:
          </Typography>
          <Typography gutterBottom>Andrey Willian</Typography>
          <Typography gutterBottom>{addresses.join(', ')}</Typography>
        </Grid>
        <Grid item xs={12} sm={6}>
          <Typography variant="h6" gutterBottom>
            Destinatário:
          </Typography>
          <Typography gutterBottom>Josnei Claudio</Typography>
          <Typography gutterBottom>{addresses.join(', ')}</Typography>
        </Grid>
        <Grid item xs={12}>
          <Divider />
        </Grid>

        <Grid item xs={12}>
          <List disablePadding>
            {products.map((product) => (
              <ListItem key={product.name} sx={{ py: 1, px: 0 }}>
                <ListItemText primary={product.name} secondary={product.desc} />
                <Typography variant="body2">{product.price}</Typography>
              </ListItem>
            ))}

            <ListItem sx={{ py: 1, px: 0 }}>
              <ListItemText primary="Total" />
              <Typography variant="subtitle1" fontSize={32} sx={{ fontWeight: 900 }}>
                R$56,32
              </Typography>
            </ListItem>
          </List>
        </Grid>
      </Grid>
    </React.Fragment>
  );
}