import { Component } from 'react';
import { httpClient } from '../httpClient';
import { styled } from '@mui/material/styles';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell, { tableCellClasses } from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import { formatCurrency } from '../utils';


const StyledTableCell = styled(TableCell)(({ theme }) => ({
	[`&.${tableCellClasses.head}`]: {
	  backgroundColor: theme.palette.common.black,
	  color: theme.palette.common.white,
	},
	[`&.${tableCellClasses.body}`]: {
	  fontSize: 14,
	},
  }));
  
  const StyledTableRow = styled(TableRow)(({ theme }) => ({
	'&:nth-of-type(odd)': {
	  backgroundColor: theme.palette.action.hover,
	},
	// hide last border
	'&:last-child td, &:last-child th': {
	  border: 0,
	},
  }));
  

export class OrderReport extends Component<any> {

	state = {
		entries: [],
	}

	componentDidMount() {
		this.getEntries();
	}

	async getEntries() {
		const response = await httpClient.get('/orders');
		this.setState({ entries: response.data });
	}

	render() {
		return (
			<TableContainer 
				className={this.props.className}
				component={Paper}
			>
			<Table sx={{ minWidth: 700 }} aria-label="customized table">
			  <TableHead>
				<TableRow>
				  <StyledTableCell>Nome</StyledTableCell>
				  <StyledTableCell align="right">Preço</StyledTableCell>
				  <StyledTableCell align="right">Quantidade</StyledTableCell>
				  <StyledTableCell align="right">Total</StyledTableCell>
				</TableRow>
			  </TableHead>
			  <TableBody>
				{this.state.entries.map((entry: any) => (
				  <StyledTableRow key={entry.id}>
					<StyledTableCell component="th" scope="row">
					  {entry.productName}
					</StyledTableCell>
					<StyledTableCell align="right">{formatCurrency(parseInt((parseInt(entry.price)/100).toFixed(2)))}</StyledTableCell>
					<StyledTableCell align="right">{entry.quantity}</StyledTableCell>
					<StyledTableCell align="right">{formatCurrency(parseInt((parseInt(entry.total)/100).toFixed(2)))}</StyledTableCell>
				  </StyledTableRow>
				))}
			  </TableBody>
			</Table>
		  </TableContainer>
		)
	}
};