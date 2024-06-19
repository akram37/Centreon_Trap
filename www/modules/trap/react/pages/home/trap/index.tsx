import { FC } from 'react';

import { Module, QueryProvider } from '@centreon/ui';

import TrapListing from "./Listing";

const TrapPage: FC = () => (
  <QueryProvider>
    <Module maxSnackbars={1} seedName="trap">
      <TrapListing/>
    </Module>
  </QueryProvider>
);

export default TrapPage;
