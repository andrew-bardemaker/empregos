import { Apple, Google } from "@mui/icons-material";
import { Box, Button, Card, Grid, TextField, Typography } from "@mui/material";

export default function LandingPage() {


    return (
        <Grid container spacing={1}>
            <Grid item xs={12}>
                <Box>
                    <Typography>
                        Encontre vagas de emprego na área da saúde
                    </Typography>
                    <Typography>
                        A Sinaxys é a maior rede de vagas para médicos, vagas para dentistas e para outros profissionais de saúde.
                    </Typography>
                    <Typography>
                        Baixe na sua loja de aplicativos:
                    </Typography>
                    <Box>
                        <Button>
                            <Apple />
                        </Button>
                        <Button>
                            <Google />
                        </Button>
                    </Box>
                </Box>
                <Card>
                    <Typography>
                        Busque por vagas
                    </Typography>
                    <TextField>

                    </TextField>
                    <TextField>

                    </TextField>
                    <TextField>

                    </TextField>
                    <Button>

                    </Button>
                </Card>
            </Grid>
            <Grid item xs={12}>
                <Box>
                    Imagem?
                </Box>
                <Box>
                    <Typography>
                        Facilite a busca por vagas de emprego na área da saúde.
                    </Typography>
                    <Typography>
                        Filtre e busque vagas de emprego na área da saúde. Candidate-se com apenas um clique e comece a trabalhar. Desenvolva a sua carreira com autonomia profissional.
                    </Typography>
                    <Button>
                        Quero buscar vagas
                    </Button>
                    <Box>
                        QR CODE
                    </Box>
                </Box>

            </Grid>
            <Grid item xs={12}>
                <Typography>
                    Estatísticas
                </Typography>
                <Grid container>
                    <Grid item xs={12}>
                        <Typography>
                            Dinamize sua carreira com a Sinaxys
                        </Typography>
                    </Grid>
                    <Grid item xs={3}>
                        Estatística 1
                    </Grid>
                    <Grid item xs={3}>
                        Estatística 2
                    </Grid>
                    <Grid item xs={3}>
                        Estatística 3
                    </Grid>
                    <Grid item xs={3}>
                        Estatística 4
                    </Grid>
                </Grid>
            </Grid>
            <Grid item xs={12}>
                Descrição2
            </Grid>
            <Grid item xs={12}>
                Parceiros?
            </Grid>
            <Grid item xs={12}>
                Feedback
            </Grid>
            <Grid item xs={12}>
                Regiões
            </Grid>
            <Grid item xs={12}>
                Instale o app
            </Grid>
        </Grid>
    )
}