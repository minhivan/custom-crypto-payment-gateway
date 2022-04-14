// let provider;
// let web3;
// let _currentAccount = null;
// let _currentNetworkId = null;
// let _currentNetworkType = null;
// let check = false;
// let _chainId = null;
// let _chainData = null;


window.clientCurrentAddress = null;
window.clientCurrentNetwork = null;
window.clientCurrentNetworkType = null;



window.isMetaMaskInstalled = () => {
    const { ethereum } = window;
    return Boolean(ethereum && ethereum.isMetaMask);
};


window.initMask = async () => {
    if (!isMetaMaskInstalled) {
        return false;
    }
    // setup env
    provider = window.ethereum;
    web3 = new Web3(provider);
    await getLoggedAccount();
    return true;
}

/*
* @description connect metamask
*/
window.onConnectMetaMask = async function () {
    if (isMetaMaskInstalled) {
        return await window.ethereum
            .request({ method: 'eth_requestAccounts' }).then((accounts) => {
                handleChangeMetamaskAccount(accounts);
                return true;
            })
            .catch((err) => {
                if (err.code === 4001) {
                    console.log("Please connect to MetaMask.");
                } else {
                    console.error(err);
                }
                return false;
            });
    }
    return false;
}



window.onSwitchNetwork = async function () {
    if (!isMetaMaskInstalled())
        return false;

    try {
        await ethereum.request({
            method: 'wallet_switchEthereumChain',
            params: [{ chainId: '0x61' }],
        });
    } catch (switchError) {
        // This error code indicates that the chain has not been added to MetaMask.
        if (switchError.code === 4902) {
            try {
                await ethereum.request({
                    method: 'wallet_addEthereumChain',
                    params: [
                        {
                            chainId: '0x61',
                            chainName: 'Binance Smart Chain - Testnet',
                            nativeCurrency: {
                                name: 'Binance Coin',
                                symbol: 'BNB',
                                decimals: 18,
                            },
                            rpcUrls: ['https://data-seed-prebsc-1-s1.binance.org:8545/'],
                            blockExplorerUrls: ['https://testnet.bscscan.com'],
                        }
                    ],
                });
            } catch (addError) {
                console.log(addError);
                return false
            }
        } else return false;
    }
    return true;
}
/*
* @description disconnect metamask
*/
window.onDisconectMetamask = async function () {
    if (provider.close) {
        await provider.close();
        provider = null;
    }
    _currentAccount = null;
    _chainId = null;
    _chainData = null;
    console.log(" [x] Disconnect metamask");
}

window.getAddress = function () {
    return window.clientCurrentAddress
}

window.handleChangeMetamaskAccount = async function (accounts) {
    if (accounts.length === 0) {
        console.log("Please connect to MetaMask.");
        window.clientCurrentAddress = null;

    } else if (accounts[0] !== _currentAccount) {
        window.clientCurrentAddress = accounts[0];
        
        provider.on("accountsChanged", async (accounts) => {
			await handleGetAccount();
		});

        provider.on('disconnect', clearAccount);

		// Subscribe to chainId change
		provider.on("chainChanged", async (chainId) => {
			await handleGetAccount();
		});

		// Subscribe to networkId change
		provider.on("networkChanged", async (networkId) => {
			await handleGetAccount();
		});

        await handleGetAccount();

    }
}

const handleGetAccount = async () => {
    window.clientCurrentNetwork = await web3.eth.net.getId();
    window.clientCurrentNetworkType = await web3.eth.net.getNetworkType();
}

const getLoggedAccount = async function () {
    try {
        if (typeof provider !== "undefined") {
            const accounts = await web3.eth.getAccounts();
            handleChangeMetamaskAccount(accounts);
        }
    } catch (e) {
        console.log(e);
        return false
    }
}

const clearAccount = () => {
    window.clientCurrentAddress = null;
    window.clientCurrentNetwork = null;
    window.clientCurrentNetworkType = null;
    if (jQuery.magnificPopup.instance.isOpen) {
        jQuery.magnificPopup.close();
    }
}


window.addEventListener("load", async () => {
    await window.initMask();
});