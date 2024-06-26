import { path } from 'ramda';

import DescriptionIcon from '@mui/icons-material/DescriptionOutlined';
import { makeStyles } from '@mui/styles';

import { SeverityCode, StatusChip, useFetchQuery } from '@centreon/ui';

const useStyles = makeStyles(() => ({
  wrapper: {
    alignItems: 'center',
    display: 'flex',
    paddingRight: 100,
  },
}));

export default (): JSX.Element => {
  const classes = useStyles();

  const { data } = useFetchQuery({
    getEndpoint: () => './api/v21.04/trap/traps',
    getQueryKey: () => ['traps'],
  });

  const traps = path<string>(['meta', 'total'], data);

  return (
    <div className={classes.wrapper}>
      <DescriptionIcon fontSize="large" style={{ color: 'white' }} />
      <StatusChip label={traps} severityCode={SeverityCode.Ok} />
    </div>
  );
};
