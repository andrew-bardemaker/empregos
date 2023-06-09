import { Upload } from "@mui/icons-material";
import { Button, Card, CardMedia, createTheme, Grid, IconButton, Typography } from "@mui/material";
import { useCallback, useEffect, useState } from "react";

export default function Gallery({ back, userType, handleChangeProgress, handleChangeGallery }) {

    const [userImage, setUserImage] = useState(null);
    const [userImageURL, setUserImageURL] = useState(null);

    const [galleryImages, setGalleryImages] = useState([]);
    const [galleryImagesURL, setGalleryImagesURL] = useState([]);

    useEffect(() => {
        handleChangeProgress(75)
    }, []);

    function userGallery() {
        var galleryImages = {
            userImage,
            galleryImages
        };
        handleChangeGallery(galleryImages);
    };

    const styles = createTheme({
        uploadBox: {
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            flexDirection: 'column',
            mb: '1em'
        },
        iconArea: {
            display: 'flex',
            justifyContent: 'start',
            alignItems: 'center',
            flexDirection: 'row',
            p: '1em'
        },
        uploadButton: {
            width: '8em',
            height: '8em'
        },
        iconButton: {
            width: '4em',
            height: '4em',
            borderRadius: '50%'
        },
        uploadText: {
            fontWeight: '700',
            textAlign: 'center',
            m: '1em'
        }
    });

    function onFileChange(e) {
        let files = e.target.files;

        setUserImageURL(URL.createObjectURL(files[0]));

        let fileReader = new FileReader();

        fileReader.readAsDataURL(files[0]);
        fileReader.onload = (event) => {
            setUserImage(event.target.result)
        }
    };

    function onGalleryChange(e) {

        let files = e.target.files;

        let image_preview = URL.createObjectURL(files[0]);

        let gallery_preview = galleryImagesURL;

        gallery_preview.push(image_preview);

        setGalleryImagesURL(gallery_preview);

        let fileReader = new FileReader();

        fileReader.readAsDataURL(files[0]);
        fileReader.onload = (event) => {
            let image = event.target.result
            let gallery_images = galleryImages;

            gallery_images.push(image);

            setGalleryImages(gallery_images);
            console.log(gallery_images);
        };
    };

    function deleteImage(index) {
        let images_preview = galleryImagesURL;

        let images = galleryImagesURL;

        images_preview = images_preview.splice(index, 1);

        images = images.splice(index, 1);

        setGalleryImagesURL(images_preview)
        setGalleryImages(images);
    };

    function buttonText() {
        if (userType === '0') {
            return 'Criar conta';
        } else {
            return 'Próximo'
        };
    };

    const CustomGalleryImages = () => useCallback(() => {
        galleryImagesURL?.map((image) => {
            return (
                <Grid item xs={4} sm={2}>
                    <CardMedia component={'img'} sx={{ borderRadius: 2 }} image={image} />
                </Grid>
            )
        });
    }, [galleryImages]);

    return (
        <Grid container spacing={1}>
            <Grid item xs={12}>
                <Typography sx={{ mt: '1em' }} variant='h2'>Ícone</Typography>
            </Grid>

            <Grid component={Card} item xs={12} sx={styles.iconArea}>
                <input
                    accept="image/*"
                    type="file"
                    id="select-image"
                    style={{ display: "none" }}
                    onChange={(e) => onFileChange(e)}
                />
                <label htmlFor="select-image">
                    <IconButton sx={styles.iconButton} component="span" >
                        {userImageURL ?
                            <CardMedia sx={styles.iconButton} component="img" image={userImageURL} />
                            :
                            <Upload sx={styles.iconButton} color="primary" variant="contained" />
                        }
                    </IconButton>
                </label>
                <Typography ml={'1em'}>
                    Insira aqui, sua melhor foto. Esta será visível para todos{userType === '0' && "recrutadores"}!
                </Typography>
            </Grid>
            {
                userType === '0' && <>
                    <Grid item xs={12}>
                        <Typography sx={{ mt: '1em' }} variant='h2'>Galeria</Typography>
                    </Grid>
                    {
                        galleryImages.length <= 6 &&
                        <Grid component={Card} item xs={12} sx={styles.uploadBox}>
                            {/* <input
                                accept="image/*"
                                type="file"
                                id="gallery-image"
                                style={{ display: "none" }}
                                onChange={(e) => onGalleryChange(e)}
                            /> */}
                            {/* <label htmlFor="gallery-image"> */}
                            <IconButton component="span" disabled>
                                <Upload sx={styles.uploadButton} color="primary" />
                            </IconButton>
                            {/* </label> */}
                            <Typography sx={styles.uploadText}>
                                Insira aqui, até 6 imagens relacionadas à você e seu trabalho
                            </Typography>
                        </Grid>
                    }
                    <CustomGalleryImages />
                </>
            }

            <Grid item xs={12} sx={{
                display: 'flex',
                justifyContent: 'space-between'
            }}>
                <Button onClick={() => back()}>
                    Voltar
                </Button>
                <Button size="large" variant="contained" onClick={() => userGallery()}>
                    {buttonText()}
                </Button>
            </Grid>
        </Grid>
    )
};