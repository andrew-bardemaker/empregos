import React, { useEffect } from 'react'

const DeliveryContext = React.createContext({})

function DeliveryProvider({children}) {

    //Delivery Informations:
    const [modality, setModality] = React.useState('');

    //Sender informations:
    const [senderName, setSenderName] = React.useState('');
    const [senderEmail, setSenderEmail] = React.useState('');
    const [senderWhatsapp, setSenderWhatsapp] = React.useState('');
    const [senderCep, setSenderCep] = React.useState('');
    const [senderAdress, setSenderAdress] = React.useState('');
    const [senderNumber, setSenderNumber] = React.useState('');
    const [senderComplement, setSenderComplement] = React.useState('');
    const [senderDistrict, setSenderDistrict] = React.useState('');
    const [senderCity, setSenderCity] = React.useState('');
    const [senderUf, setSenderUf] = React.useState('');
    const [senderProduct, setSenderProduct] = React.useState('');
    const [senderSaveAdress, setSenderSaveAdress] = React.useState(false);

    //Recipient informations:
    const [recipientName, setRecipientName] = React.useState('');
    const [recipientEmail, setRecipientEmail] = React.useState('');
    const [recipientWhatsapp, setRecipientWhatsapp] = React.useState('');
    const [recipientCep, setRecipientCep] = React.useState('');
    const [recipientAdress, setRecipientAdress] = React.useState('');
    const [recipientNumber, setRecipientNumber] = React.useState('');
    const [recipientComplement, setRecipientComplement] = React.useState('');
    const [recipientDistrict, setRecipientDistrict] = React.useState('');
    const [recipientCity, setRecipientCity] = React.useState('');
    const [recipientUf, setRecipientUf] = React.useState('');
    const [recipientProduct, setRecipientProduct] = React.useState('');
    const [recipientSaveAdress, setRecipientSaveAdress] = React.useState(false);

    //Set user informations as sender default data 
    useEffect(() => {

    }, [])

    return (
        <DeliveryContext.Provider
            value={{
                //Delivery modality
                modality, setModality: this.setModality,
                //Sender's data sharing
                senderName, setSenderName,
                senderEmail, setSenderEmail,
                senderWhatsapp, setSenderWhatsapp,
                senderCep, setSenderCep,
                senderAdress, setSenderAdress,
                senderNumber, setSenderNumber,
                senderComplement, setSenderComplement,
                senderDistrict, setSenderDistrict,
                senderCity, setSenderCity,
                senderUf, setSenderUf,
                senderProduct, setSenderProduct,
                senderSaveAdress, setSenderSaveAdress,

                //Recipient's data sharing
                recipientName, setRecipientName,
                recipientEmail, setRecipientEmail,
                recipientWhatsapp, setRecipientWhatsapp,
                recipientCep, setRecipientCep,
                recipientAdress, setRecipientAdress,
                recipientNumber, setRecipientNumber,
                recipientComplement, setRecipientComplement,
                recipientDistrict, setRecipientDistrict,
                recipientCity, setRecipientCity,
                recipientUf, setRecipientUf,
                recipientProduct, setRecipientProduct,
                recipientSaveAdress, setRecipientSaveAdress,
            }}
        >
            {children}
        </DeliveryContext.Provider>
    )
}

export default DeliveryContext;

export { DeliveryProvider }