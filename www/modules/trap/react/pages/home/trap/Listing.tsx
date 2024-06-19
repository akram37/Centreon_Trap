import { FC } from 'react';

import {
  ColumnType,
  Listing,
  ListingModel,
  ListingPage,
  SeverityCode,
  StatusChip,
  useFetchQuery,
  Column,
} from '@centreon/ui';

const ComponentColumn = (): JSX.Element => (
  <StatusChip label="OK" severityCode={SeverityCode.Ok} />
);

const configuration: Array<Column> = [
  {
    getFormattedString: ({ name }): string => name,
    id: 'name',
    label: 'Name',
    sortable: false,
    type: ColumnType.string,
  },
  {
    getFormattedString: ({ description }): string => description,
    id: 'description',
    label: 'Description',
    sortable: false,
    type: ColumnType.string,
  },
  {
    Component: ComponentColumn,
    id: '#',
    label: 'Custom',
    sortable: false,
    type: ColumnType.component,
  },
];

const TrapListing: FC = () => {
  const { data, isLoading } = useFetchQuery<
    ListingModel<{ description: string; id: string; name: string }>
  >({
    getEndpoint: () => './api/v21.04/trap/traps',
    getQueryKey: () => ['traps'],
  });

  const traps = data?.result || [];

  return (
    <ListingPage
      filter={<div />}
      listing={
        <Listing
          columns={configuration}
          loading={isLoading}
          rows={traps}
          totalRows={traps.length}
        />
      }
    />
  );
};

export default TrapListing;
